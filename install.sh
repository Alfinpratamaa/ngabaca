#!/bin/bash

# Ngabaca Project Setup Script
# Usage: ./install.sh YOUR_ENCRYPTION_KEY

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if encryption key is provided
if [ $# -eq 0 ]; then
    print_error "Encryption key required!"
    echo "Usage: ./install.sh YOUR_ENCRYPTION_KEY"
    exit 1
fi

ENCRYPTION_KEY=$1

echo "🚀 Ngabaca Project Setup"
echo "========================"

# Function to check requirements
check_requirements() {
    local missing_requirements=()
    
    if ! command -v php &> /dev/null || ! php -r "exit(PHP_MAJOR_VERSION >= 8 && PHP_MINOR_VERSION >= 3 ? 0 : 1);" 2>/dev/null; then
        missing_requirements+=("PHP 8.3+")
    fi
    
    if ! command -v composer &> /dev/null; then
        missing_requirements+=("Composer")
    fi
    
    if ! command -v node &> /dev/null; then
        missing_requirements+=("Node.js")
    fi
    
    if ! command -v npm &> /dev/null; then
        missing_requirements+=("npm")
    fi
    
    if [ ${#missing_requirements[@]} -gt 0 ]; then
        print_error "Missing requirements: ${missing_requirements[*]}"
        print_error "Please install the missing requirements manually and run this script again."
        return 1
    fi
    
    return 0
}

# Check requirements
print_status "Checking system requirements..."

if ! check_requirements; then
    exit 1
fi

print_success "All system requirements are installed!"

# Verify versions
echo ""
print_status "Installed versions:"
echo "PHP: $(php --version 2>/dev/null | head -n 1)"
echo "Composer: $(composer --version 2>/dev/null | head -n 1)"
echo "Node.js: $(node --version 2>/dev/null)"
echo "NPM: $(npm --version 2>/dev/null)"
echo ""

# Install PHP dependencies
print_status "Installing PHP dependencies..."
if composer install --no-interaction --prefer-dist --optimize-autoloader > /dev/null 2>&1; then
    print_success "PHP dependencies installed!"
else
    print_error "Failed to install PHP dependencies."
    exit 1
fi

# Environment file setup
print_status "Setting up environment file..."
rm -f .env 2>/dev/null

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

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
if npm install > /dev/null 2>&1; then
    print_success "Node.js dependencies installed!"
    chmod +x node_modules/.bin/* 2>/dev/null || true
else
    print_error "Failed to install Node.js dependencies."
    exit 1
fi

# Generate application key
print_status "Setting up application..."
if ! grep -q "^APP_KEY=.\+$" .env; then
    php artisan key:generate --force > /dev/null 2>&1
    print_success "Application key generated!"
else
    print_success "Application key already exists!"
fi

# Set permissions
print_status "Setting file permissions..."
sudo chown -R ubuntu:ubuntu /home/ubuntu/ngabaca
sudo chmod -R 755 /home/ubuntu/ngabaca
print_success "File permissions set!"

# Clear cache
print_status "Clearing application cache..."
php artisan cache:clear > /dev/null 2>&1 || true
php artisan config:clear > /dev/null 2>&1 || true
php artisan view:clear > /dev/null 2>&1 || true
php artisan route:clear > /dev/null 2>&1 || true
print_success "Cache cleared!"

# Database setup
print_status "Setting up database..."
if php artisan migrate --force > /dev/null 2>&1; then
    print_success "Database migrated!"
    
    read -p "Do you want to seed the database? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        if php artisan db:seed --force > /dev/null 2>&1; then
            print_success "Database seeded!"
        else
            print_warning "Database seeding failed."
        fi
    fi
else
    print_warning "Database migration failed. Please check your .env configuration."
fi

# Storage link
print_status "Creating storage link..."
if php artisan storage:link > /dev/null 2>&1; then
    print_success "Storage link created!"
else
    print_warning "Storage link creation failed (might already exist)."
fi

# Install Laravel Pail
print_status "Installing Laravel Pail..."
if composer require laravel/pail --dev --no-interaction > /dev/null 2>&1; then
    print_success "Laravel Pail installed!"
else
    print_warning "Laravel Pail installation failed (might already be installed)."
fi

echo ""
echo "🎉 Setup completed successfully!"
echo "================================"
echo ""
echo "Next steps:"
echo "1. Start development server: composer run dev"
echo "2. Visit: http://localhost:8000"
echo ""
print_success "Happy coding! 🚀"
