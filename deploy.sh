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
    # Setel kepemilikan dan izin untuk APP_DIR secara keseluruhan
    sudo chown -R $USER:www-data "$APP_DIR"
    sudo chmod -R 775 "$APP_DIR" # Izinkan grup menulis
    echo "Cloning repository from $REPO_URL..."
    git clone "$REPO_URL" "$APP_DIR"
    echo "Repository cloned."
    cd "$APP_DIR"
else # Jika direktori sudah ada, navigasi dan pull
    echo "Directory $APP_DIR exists. Navigating and pulling latest changes."
    cd "$APP_DIR"
    
    # Set ownership of the entire directory to current user for Git operations
    echo "Setting temporary ownership for Git operations..."
    sudo chown -R $USER:$USER "$APP_DIR"
    
    echo "Fetching latest changes from Git..."
    git fetch origin production
    git reset --hard origin/production # Reset lokal ke kondisi branch production
    git pull origin production
    echo "Git pull completed."
fi

# --- Setup Laravel directories dan permissions SEBELUM composer install ---
echo "Creating Laravel directories..."
mkdir -p storage/logs storage/framework/{sessions,views,cache} bootstrap/cache

echo "Setting permissions for Laravel directories..."
sudo chown -R $USER:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
echo "Laravel directories and permissions set up successfully."
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
fi
# --- Akhir Dekripsi .env ---

# --- Instal Composer dependencies ---
echo "Installing Composer dependencies..."
if ! command -v composer &> /dev/null; then
    echo "Error: Composer not found. Please install Composer on EC2."
    exit 1
fi

# Set COMPOSER_ALLOW_SUPERUSER to avoid warnings if running as root
export COMPOSER_ALLOW_SUPERUSER=1

composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
echo "Composer dependencies installed successfully."


# Clear cache dan recreate cache Laravel
echo "Clearing and recreating Laravel cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan clear-compiled # Clear compiled classes

# Generate application key jika belum ada di .env yang baru didekripsi
if [ -z "$(grep -E '^APP_KEY=' .env)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

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

# --- Final permission check ---
echo "Final permission check and cleanup..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Ensure proper ownership for the entire application directory
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 755 "$APP_DIR"
# But keep storage and bootstrap/cache writable
sudo chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# ---------- SET UP LOG DIRECTORY ----------------
# Create logs directory structure if it doesn't exist
mkdir -p storage/logs
echo "Laravel log directory prepared."


echo "Deployment finished successfully!"
echo "Application is now live and ready to serve requests."