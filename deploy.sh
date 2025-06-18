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
exec > >(tee -a "$DEPLOY_LOG_FILE") 2>&1 # Redirect semua output ke log file

echo "Timestamp: $(date)"

# --- Inisialisasi NVM dan PATH (Penting untuk NPM) ---
# Asumsikan NVM terinstal di ~/.nvm
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
# Pilih versi node yang akan digunakan, sesuaikan dengan yang ada di EC2 Anda
# Coba gunakan versi default, jika tidak ada, instal node LTS terbaru, lalu gunakan
nvm use default || nvm install node --default || nvm use node
echo "NVM initialized and Node.js version set."
export PATH=$PATH:/usr/local/bin:/usr/bin:/bin # Tambahkan path umum untuk binaries sebagai fallback
echo "Current PATH: $PATH"
# --- Akhir Inisialisasi NVM/PATH ---

# --- Logika Git Clone/Pull yang Robust ---
if [ ! -d "$APP_DIR" ]; then # Jika direktori APP_DIR belum ada
    echo "Directory $APP_DIR does not exist. Creating and cloning repository..."
    sudo mkdir -p "$APP_DIR" # Buat direktori aplikasi sebagai root
    sudo chown -R $USER:www-data "$APP_DIR" # Ubah kepemilikan ke user SSH dan grup web server
    sudo chmod -R 775 "$APP_DIR" # Atur izin
    echo "Cloning repository from $REPO_URL..."
    git clone "$REPO_URL" "$APP_DIR" # Clone ke direktori APP_DIR
    echo "Repository cloned."
    cd "$APP_DIR"
    # Setel izin awal untuk storage/bootstrap/cache setelah klon pertama
    echo "Setting initial storage and cache permissions after first clone..."
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
else # Jika direktori sudah ada, navigasi dan pull
    echo "Directory $APP_DIR exists. Navigating and pulling latest changes."
    cd "$APP_DIR"
    
    # --- KRUSIAL: Bersihkan direktori cache dan storage sebelum git pull/reset ---
    echo "Cleaning up storage and bootstrap/cache directories before Git operations..."
    # Pastikan kepemilikan dan izin direktori utama storage/bootstrap/cache sudah benar
    sudo chown -R www-data:www-data storage bootstrap/cache || true
    sudo chmod -R 775 storage bootstrap/cache || true

    # Hapus konten yang mungkin menyebabkan konflik izin saat git reset/pull
    sudo rm -rf storage/framework/cache/data/* || true
    sudo rm -rf storage/framework/views/* || true
    sudo rm -rf bootstrap/cache/* || true
    sudo rm -f storage/logs/*.log || true # Hapus log file lama

    echo "Storage and cache content cleared."
    # --- Akhir Perubahan Cleanup ---

    echo "Fetching latest changes from Git..."
    git fetch origin production # Ambil perubahan dari branch production
    git reset --hard origin/production # Reset lokal ke kondisi branch production
    git pull origin production # Pull perubahan terbaru
    echo "Git pull completed."
fi
# --- Akhir Logika Git ---

# --- Dekripsi .env.enc menjadi .env ---
if [ -z "$LARAVEL_ENV_ENCRYPTION_KEY" ]; then
    echo "Error: LARAVEL_ENV_ENCRYPTION_KEY not set. Cannot decrypt .env file."
    exit 1
fi

if [ -f .env.enc ]; then
    echo "Decrypting .env.enc to .env..."
    # Periksa apakah openssl tersedia, jika tidak, berikan pesan error yang jelas
    if ! command -v openssl &> /dev/null; then
        echo "Error: openssl not found. Please install openssl on EC2 to decrypt .env.enc"
        exit 1
    fi
    openssl enc -aes-256-cbc -d -in .env.enc -out .env -k "$LARAVEL_ENV_ENCRYPTION_KEY"
    echo ".env decrypted successfully."
else
    echo "Warning: .env.enc not found. Skipping decryption."
    # Opsional: Jika .env.enc tidak ada dan .env.example yang digunakan
    # if [ ! -f .env ] && [ -f .env.example ]; then
    #     cp .env.example .env
    #     echo ".env created from .env.example as .env.enc was not found."
    # fi
fi
# --- Akhir Dekripsi .env ---

# Masuk ke maintenance mode Laravel
echo "Entering maintenance mode..."
php artisan down || true

# Instal Composer dependencies
echo "Installing Composer dependencies..."
# Cek apakah composer terpasang
if ! command -v composer &> /dev/null; then
    echo "Error: Composer not found. Please install Composer on EC2."
    exit 1
fi
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear cache dan recreate cache Laravel
echo "Clearing and recreating Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan clear-compiled # Clear compiled classes

# Optimize Laravel application
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
# --- Akhir NPM ---

# Jalankan database migrations
echo "Running database migrations ..."
php artisan migrate --force

# Generate application key jika belum ada di .env yang baru didekripsi
if [ -z "$(grep -E '^APP_KEY=' .env)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Atur izin direktori storage dan bootstrap/cache (ini adalah penyesuaian akhir)
echo "Final adjustment for storage and cache permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Keluar dari maintenance mode
echo "Exiting maintenance mode..."
php artisan up

echo "Deployment finished successfully!"