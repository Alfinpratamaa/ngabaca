#!/bin/bash
set -e # Keluar jika ada perintah yang gagal

echo "Deployment started on EC2 ..."
# Pastikan ini hanya dieksekusi oleh bash dan hindari masalah path dengan "~"
# source home/$USER/.bashrc || true # Baris ini seringkali tidak diperlukan atau bermasalah di sesi non-interaktif SSH, bisa dihapus.

APP_DIR="/var/www/ngabaca" # Direktori aplikasi di EC2
REPO_URL="https://github.com/Alfinpratamaa/ngabaca.git" # URL Repositori Git Anda

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"  # This loads nvm
[ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"  # This loads nvm bash_completion
nvm use default || nvm install node --default || nvm use node
echo "NVM initialized and Node.js version set."


export PATH=$PATH:/usr/local/bin:/usr/bin:/bin # Tambahkan path umum untuk binaries
echo "Current PATH: $PATH"

LOG_DIR="/home/$USER/app_deployment_logs" # Direktori log deployment
DEPLOY_LOG_FILE="$LOG_DIR/$(date +%Y-%m-%d-%H-%M-%S)_deploy.log"

mkdir -p "$LOG_DIR"
exec > >(tee -a "$DEPLOY_LOG_FILE") 2>&1

echo "Timestamp: $(date)"

# --- Perubahan di sini: Logika Git Clone/Pull yang lebih baik ---
if [ ! -d "$APP_DIR" ]; then # Jika direktori APP_DIR belum ada
    echo "Directory $APP_DIR does not exist. Creating and cloning repository..."
    sudo mkdir -p "$APP_DIR" # Buat direktori aplikasi sebagai root
    sudo chown -R $USER:www-data "$APP_DIR" # Ubah kepemilikan
    sudo chmod -R 775 "$APP_DIR" # Atur izin
    echo "Cloning repository from $REPO_URL..."
    git clone "https://github.com/Alfinpratamaa/ngabaca.git" "$APP_DIR" # Clone ke direktori APP_DIR
    echo "Repository cloned."
    cd "$APP_DIR"
    # Lakukan initial permission set untuk storage/bootstrap/cache setelah clone pertama
    echo "Setting initial storage and cache permissions after first clone..."
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
else # Jika direktori sudah ada, navigasi dan pull
    echo "Directory $APP_DIR exists. Navigating and pulling latest changes."
    cd "$APP_DIR"
    # --- Perubahan di sini: Setel ulang izin SEBELUM git pull/reset ---
    echo "Resetting permissions for storage and cache before git pull..."
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
    # --- Akhir Perubahan ---

    echo "Fetching latest changes from Git..."
    git fetch origin production # Ambil perubahan dari branch production
    git reset --hard origin/production # Reset lokal ke kondisi branch production
    git pull origin production # Pull perubahan terbaru
    echo "Git pull completed."
fi
# --- Akhir Perubahan Git Logic ---

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

echo "checking npm version..."
NPM_BIN=$(command -v npm || true)
if [ -z "$NPM_BIN" ]; then
    echo "ERROR: npm command not found. Please ensure Node.js and npm are correctly installed and available in a standard PATH."
    exit 1
fi
echo "NPM found at: $NPM_BIN"
"$NPM_BIN" --version

echo "Installing NPM dependencies and compiling assets..."
"$NPM_BIN" install --silent --no-progress
"$NPM_BIN" run build

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