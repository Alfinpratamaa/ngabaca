#!/bin/bash
set -e # Keluar jika ada perintah yang gagal

echo "Deployment started on EC2 ..."

APP_DIR="/var/www/ngabaca" # Direktori aplikasi di EC2
LOG_DIR="/var/log/app_deployment" # Direktori log deployment
DEPLOY_LOG_FILE="$LOG_DIR/$(date +%Y-%m-%d-%H-%M-%S)_deploy.log"

# Pastikan direktori log ada
mkdir -p "$LOG_DIR"
exec > >(tee -a "$DEPLOY_LOG_FILE") 2>&1 # Redirect semua output ke log file

echo "Timestamp: $(date)"

# Masuk ke direktori aplikasi
cd "$APP_DIR" || {
    echo "Directory $APP_DIR does not exist. Cloning repository..."
    # Jika direktori tidak ada, lakukan git clone
    # GITHUB_REPOSITORY akan di-pass dari workflow GitHub Actions (misal: your_user/your_repo_name)
    git clone https://github.com/${GITHUB_REPOSITORY} .
    echo "Repository cloned."
}

# Pergi ke branch production dan pull terbaru
echo "Fetching latest changes from Git..."
git fetch origin production
git reset --hard origin/production
git pull origin production

echo "Git pull completed."

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

# --- Bagian Baru: Dekripsi .env.enc menjadi .env ---
# Memastikan OPENSLL_KEY tersedia dari GitHub Actions (variabel lingkungan)
if [ -z "$LARAVEL_ENV_ENCRYPTION_KEY" ]; then
    echo "Error: LARAVEL_ENV_ENCRYPTION_KEY not set. Cannot decrypt .env file."
    exit 1
fi

if [ -f .env.enc ]; then
    echo "Decrypting .env.enc to .env..."
    # Gunakan OpenSSL untuk mendekripsi
    openssl enc -aes-256-cbc -d -in .env.enc -out .env -k "$LARAVEL_ENV_ENCRYPTION_KEY"
    echo ".env decrypted successfully."
else
    echo "Warning: .env.enc not found. Skipping decryption."
    # Opsional: bisa copy dari .env.example jika .env.enc tidak ada dan tidak diharapkan
    # cp .env.example .env
fi

# Generate application key jika belum ada di .env yang baru didekripsi
if [ -z "$(grep -E '^APP_KEY=' .env)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi
# --- Akhir Bagian Baru ---

# Atur izin direktori storage dan bootstrap/cache
echo "Setting storage and cache permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Keluar dari maintenance mode
echo "Exiting maintenance mode..."
php artisan up

echo "Deployment finished successfully!"