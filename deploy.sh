#!/bin/bash
set -e # Keluar jika ada perintah yang gagal

echo "Deployment started on EC2 ..."

APP_DIR="/var/www/ngabaca" # Direktori aplikasi di EC2
REPO_URL="https://github.com/Alfinpratamaa/ngabaca.git" # URL Repositori Git Anda
# Jika repositori privat, Anda mungkin perlu mengkonfigurasi kredensial Git di EC2.
# Untuk repo privat, bisa juga gunakan SSH: REPO_URL="git@github.com:your_user/your_repo.git" dan pastikan kunci SSH untuk Git sudah ada di EC2.

LOG_DIR="/home/$USER/app_deployment_logs" # Direktori log deployment
DEPLOY_LOG_FILE="$LOG_DIR/$(date +%Y-%m-%d-%H-%M-%S)_deploy.log"

# Pastikan direktori log ada
mkdir -p "$LOG_DIR"
exec > >(tee -a "$DEPLOY_LOG_FILE") 2>&1 # Redirect semua output ke log file

echo "Timestamp: $(date)"

# --- Perubahan di sini: Logika Git Clone/Pull yang lebih baik ---
if [ ! -d "$APP_DIR" ]; then # Jika direktori APP_DIR belum ada
    echo "Directory $APP_DIR does not exist. Creating and cloning repository..."
    sudo mkdir -p "$APP_DIR" # Buat direktori aplikasi sebagai root
    sudo chown -R $USER:www-data "$APP_DIR" # Ubah kepemilikan
    sudo chmod -R 775 "$APP_DIR" # Atur izin
    git clone "$REPO_URL" "$APP_DIR" # Clone ke direktori APP_DIR
    echo "Repository cloned."
    cd "$APP_DIR"
else # Jika direktori sudah ada, navigasi dan pull
    echo "Directory $APP_DIR exists. Navigating and pulling latest changes."
    cd "$APP_DIR"
    echo "Fetching latest changes from Git..."
    git fetch origin production # Ambil perubahan dari branch production
    git reset --hard origin/production # Reset lokal ke kondisi branch production
    git pull origin production # Pull perubahan terbaru
    echo "Git pull completed."
fi
# --- Akhir Perubahan ---

# Masuk ke maintenance mode Laravel
echo "Entering maintenance mode..."
php artisan down || true

# Instal Composer dependencies
echo "Installing Composer dependencies..."
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

# Install NPM dependencies dan compile assets (jika ada frontend)
echo "Installing NPM dependencies and compiling assets..."
npm install --silent --no-progress
npm run build # Atau npm run prod, sesuaikan dengan package.json Anda

# Jalankan database migrations
echo "Running database migrations..."
php artisan migrate --force

# --- Bagian Dekripsi .env.enc menjadi .env ---
if [ -z "$LARAVEL_ENV_ENCRYPTION_KEY" ]; then
    echo "Error: LARAVEL_ENV_ENCRYPTION_KEY not set. Cannot decrypt .env file."
    exit 1
fi

if [ -f .env.enc ]; then
    echo "Decrypting .env.enc to .env..."
    openssl enc -aes-256-cbc -d -in .env.enc -out .env -k "$LARAVEL_ENV_ENCRYPTION_KEY"
    echo ".env decrypted successfully."
else
    echo "Warning: .env.enc not found. Skipping decryption."
fi

# Generate application key jika belum ada di .env yang baru didekripsi
if [ -z "$(grep -E '^APP_KEY=' .env)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi
# --- Akhir Bagian Dekripsi ---

# Atur izin direktori storage dan bootstrap/cache
echo "Setting storage and cache permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Keluar dari maintenance mode
echo "Exiting maintenance mode..."
php artisan up

echo "Deployment finished successfully!"