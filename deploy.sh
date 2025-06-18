#!/bin/bash
set -e # Keluar jika ada perintah yang gagal

echo "Deployment started on EC2 ..."

# Variabel konfigurasi
APP_DIR="/var/www/ngabaca" # Direktori aplikasi di EC2
REPO_URL="https://github.com/Alfinpratamaa/ngabaca.git" # URL Repositori Git Anda (Sesuaikan jika privat atau berbeda)

# Setup Logging
LOG_DIR="/home/$USER/app_deployment_logs" # Direktori log deployment
DEPLOY_LOG_FILE="$LOG_DIR/$(date +%Y-%m-%d-%H-%M-%S)_deploy.log"

mkdir -p "$LOG_DIR"
exec > >(tee -a "$DEPLOY_LOG_FILE") 2>&1

echo "Timestamp: $(date)"

# --- Inisialisasi NVM dan PATH (Penting untuk NPM) ---
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
nvm use default || nvm install node --default || nvm use node
echo "NVM initialized and Node.js version set."
export PATH=$PATH:/usr/local/bin:/usr/bin:/bin
echo "Current PATH: $PATH"
# --- Akhir Inisialisasi NVM/PATH ---

# --- Logika Git Clone/Pull yang Robust ---
if [ ! -d "$APP_DIR" ]; then
    echo "Directory $APP_DIR does not exist. Creating and cloning repository..."
    sudo mkdir -p "$APP_DIR"
    sudo chown -R $USER:www-data "$APP_DIR"
    sudo chmod -R 775 "$APP_DIR"
    echo "Cloning repository from $REPO_URL..."
    git clone "$REPO_URL" "$APP_DIR"
    echo "Repository cloned."
    cd "$APP_DIR"
else # Jika direktori sudah ada, navigasi dan pull
    echo "Directory $APP_DIR exists. Navigating and pulling latest changes."
    cd "$APP_DIR"
    
    # Set ownership of the entire directory to current user for Git operations
    echo "Setting temporary ownership for Git operations..."
    sudo chown -R $USER:$USER "$APP_DIR" # Pastikan user saat ini bisa menulis
    
    echo "Fetching latest changes from Git..."
    git fetch origin production
    git reset --hard origin/production
    git pull origin production
    echo "Git pull completed."
fi

# --- Setup Laravel directories dan permissions UTAMA ---
# Pastikan direktori ini ada dan memiliki izin yang benar SEBELUM Composer/Artisan
echo "Ensuring Laravel directories exist and setting initial permissions..."
sudo mkdir -p "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" "$APP_DIR/storage/framework/sessions" "$APP_DIR/storage/framework/views" "$APP_DIR/storage/framework/cache/data" "$APP_DIR/storage/logs" || true

# Hapus semua konten dari cache dan storage (kecuali .gitignore) sebelum Composer
# Ini untuk memastikan clean state dan mengatasi masalah file lama yang mungkin terkunci
sudo rm -rf storage/framework/cache/data/* || true
sudo rm -rf storage/framework/views/* || true
sudo rm -rf bootstrap/cache/* || true
sudo rm -f storage/logs/*.log || true

# Setel izin dasar untuk direktori ini agar www-data bisa menulis
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache # Memberi izin tulis untuk grup

echo "Laravel directories cleaned and initial permissions set."
# --- Akhir Setup Direktori Laravel ---

# --- Dekripsi .env.enc menjadi .env ---
if [ -z "$LARAVEL_ENV_ENCRYPTION_KEY" ]; then
    echo "Error: LARAVEL_ENV_ENCRYPTION_KEY not set. Cannot decrypt .env file."
    exit 1
fi

if [ -f .env.enc ]; then
    echo "Decrypting .env.enc to .env..."
    if ! command -v openssl &> /dev/null; then
        echo "Error: openssl not found. Please install openssl on EC2 to decrypt .env.enc"
        exit 1
    fi
    openssl enc -aes-256-cbc -d -in .env.enc -out .env -k "$LARAVEL_ENV_ENCRYPTION_KEY"
    echo ".env decrypted successfully."
else
    echo "Warning: .env.enc not found. Skipping decryption."
    # Jika .env.enc tidak ada, copy dari .env.example
    if [ ! -f .env ] && [ -f .env.example ]; then
        cp .env.example .env
        echo ".env created from .env.example as .env.enc was not found."
    fi
fi
# --- Akhir Dekripsi .env ---

# --- Instal Composer dependencies (Ini harus dijalankan SEBELUM php artisan down) ---
echo "Installing Composer dependencies..."
if ! command -v composer &> /dev/null; then
    echo "Error: Composer not found. Please install Composer on EC2."
    exit 1
fi
# Jalankan composer install sebagai user www-data untuk menghindari masalah izin?
# composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
# Jika masih ada masalah izin, coba jalankan dengan sudo -u www-data
sudo -u www-data composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader || composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "Composer dependencies installed successfully."

# Masuk ke maintenance mode Laravel (SEKARANG SUDAH ADA vendor/autoload.php)
echo "Entering maintenance mode..."
php artisan down || true

# Generate application key jika belum ada di .env yang baru didekripsi
if [ -z "$(grep -E '^APP_KEY=' .env)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Clear cache dan recreate cache Laravel
echo "Clearing and recreating Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan clear-compiled # Clear compiled classes

# Optimize Laravel application
echo "Optimizing Laravel application..."
php artisan optimize

# --- NPM Dependencies dan Compile Assets ---
echo "Checking npm version and compiling assets..."
NPM_BIN=$(command -v npm || true)
if [ -z "$NPM_BIN" ]; then
    echo "ERROR: npm command not found. Please ensure Node.js and npm are correctly installed."
    exit 1
fi
echo "NPM found at: $NPM_BIN"
"$NPM_BIN" --version

echo "Installing NPM dependencies and compiling assets..."
"$NPM_BIN" install --silent --no-progress
"$NPM_BIN" run build # Atau npm run prod, sesuaikan dengan package.json Anda
echo "NPM build completed successfully."
# --- Akhir NPM ---

# Jalankan database migrations
echo "Running database migrations ..."
php artisan migrate --force

# --- Final permission adjustment ---
echo "Final adjustment for storage and cache permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
# Ensure proper ownership for the entire application directory, especially for web server
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# --- Keluar dari maintenance mode ---
echo "Exiting maintenance mode..."
php artisan up

echo "Deployment finished successfully!"
echo "Application is now live and ready to serve requests."