#!/bin/bash

# Ngabaca Project Setup Script
# Usage: ./install.sh YOUR_ENCRYPTION_KEY

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
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

ENCRYPTION_KEY="$1" # Use quotes to handle keys with special characters or spaces

echo "🚀 Setting up Ngabaca project..."
echo "=================================="

# Check if required commands exist
print_status "Checking requirements..."

if ! command -v php &>/dev/null; then
    print_error "PHP is not installed"
    exit 1
fi

if ! command -v composer &>/dev/null; then
    print_error "Composer is not installed"
    exit 1
fi

if ! command -v node &>/dev/null; then
    print_error "Node.js is not installed"
    exit 1
fi

if ! command -v npm &>/dev/null; then
    print_error "NPM is not installed"
    exit 1
fi

print_success "All requirements met!"

---

# Install PHP dependencies
print_status "Installing PHP dependencies..."
if composer install --no-dev --optimize-autoloader; then
    print_success "PHP dependencies installed!"
else
    print_error "Failed to install PHP dependencies. Please check your Composer setup."
    exit 1 # Exit if Composer fails, as artisan won't work
fi

# Install Laravel Pail (for combined dev server)
print_status "Installing Laravel Pail for combined development server..."
if composer require laravel/pail --dev; then
    print_success "Laravel Pail installed!"
else
    print_warning "Failed to install Laravel Pail. You may need to run 'php artisan serve' and 'npm run dev' separately."
    # Do not exit here, as the project can still function without Pail
fi

---

# Decrypt environment file
print_status "Cleaning up old .env file (if any)..."
# Remove existing .env file to ensure a clean decryption
rm -f .env 2>/dev/null # Use -f to force remove and suppress errors if file doesn't exist

print_status "Decrypting environment file..."

if [ ! -f ".env.encrypted" ]; then
    print_error ".env.encrypted file not found!"
    exit 1
fi

# Use Laravel's built-in decryption via environment variable
# Make sure the ENCRYPTION_KEY is the base64 encoded key (e.g., base64:YOUR_KEY)
if LARAVEL_ENV_ENCRYPTION_KEY="$ENCRYPTION_KEY" php artisan env:decrypt; then
    print_success "Environment file decrypted successfully!"
else
    print_error "Failed to decrypt environment file. Check your encryption key or if .env.encrypted is valid!"
    exit 1
fi

---

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
if npm install; then
    print_success "Node.js dependencies installed!"
else
    print_error "Failed to install Node.js dependencies"
    exit 1
fi

# Set proper permissions
print_status "Setting file permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null
print_success "File permissions set!"

# Clear cache
print_status "Clearing application cache..."
php artisan cache:clear &>/dev/null
php artisan config:clear &>/dev/null
php artisan view:clear &>/dev/null
php artisan route:clear &>/dev/null
print_success "Cache cleared!"

# Database setup
print_status "Setting up database..."
if php artisan migrate --force; then
    print_success "Database migrated!"

else
    print_warning "Database migration failed. Please check your database configuration."
fi

# Storage link
print_status "Creating storage link..."
if php artisan storage:link; then
    print_success "Storage link created!"
else
    print_warning "Failed to create storage link"
fi

echo ""
echo "🎉 Setup completed successfully!"
echo "=================================="
echo ""
echo "Next steps:"
# Updated next steps to suggest `composer run dev`
echo "1. Start development server & frontend build: composer run dev"
echo "2. Visit: http://localhost:8000 (or the address from 'php artisan serve')"
echo ""
print_success "Happy coding! 🚀"
