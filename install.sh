#!/bin/bash

# Ngabaca Project Setup Script
# Usage: ./install.sh YOUR_ENCRYPTION_KEY [--skip-system-install]

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

print_install() {
    echo -e "${PURPLE}[INSTALL]${NC} $1"
}

# Check if encryption key is provided
if [ $# -eq 0 ]; then
    print_error "Encryption key required!"
    echo "Usage: ./install.sh YOUR_ENCRYPTION_KEY [--skip-system-install]"
    exit 1
fi

ENCRYPTION_KEY=$1
SKIP_SYSTEM_INSTALL=false

# Check for skip flag
if [ "$2" = "--skip-system-install" ]; then
    SKIP_SYSTEM_INSTALL=true
fi

echo "🚀 Setting up Ngabaca project..."
echo "=================================="

# Function to detect OS
detect_os() {
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        if [ -f /etc/debian_version ]; then
            echo "debian"
        elif [ -f /etc/redhat-release ]; then
            echo "redhat"
        elif [ -f /etc/arch-release ]; then
            echo "arch"
        else
            echo "linux"
        fi
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        echo "macos"
    else
        echo "unknown"
    fi
}

OS=$(detect_os)
print_status "Detected OS: $OS"

# Function to install system packages
install_system_requirements() {
    if [ "$SKIP_SYSTEM_INSTALL" = true ]; then
        print_warning "Skipping system requirements installation"
        return
    fi

    print_install "Installing system requirements..."
    
    case $OS in
        "debian")
            sudo apt update
            
            # Install PHP and extensions
            print_install "Installing PHP and extensions..."
            sudo apt install -y php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath php-pgsql php-intl php-soap php-xmlrpc php-opcache
            
            # Install other requirements
            sudo apt install -y curl wget git unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release
            
            # Install PostgreSQL
            print_install "Installing PostgreSQL..."
            sudo apt install -y postgresql postgresql-contrib
            
            # Install MySQL
            print_install "Installing MySQL..."
            sudo apt install -y mysql-server mysql-client
            ;;
            
        "redhat")
            sudo yum update -y
            
            # Install EPEL repository
            sudo yum install -y epel-release
            
            # Install PHP and extensions
            print_install "Installing PHP and extensions..."
            sudo yum install -y php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath php-pgsql php-intl php-soap php-xmlrpc php-opcache
            
            # Install other requirements
            sudo yum install -y curl wget git unzip
            
            # Install PostgreSQL
            print_install "Installing PostgreSQL..."
            sudo yum install -y postgresql postgresql-server postgresql-contrib
            sudo postgresql-setup initdb
            
            # Install MySQL
            print_install "Installing MySQL..."
            sudo yum install -y mysql-server mysql
            ;;
            
        "arch")
            sudo pacman -Syu --noconfirm
            
            # Install PHP and extensions
            print_install "Installing PHP and extensions..."
            sudo pacman -S --noconfirm php php-fpm php-gd php-intl php-pgsql php-sqlite
            
            # Install other requirements
            sudo pacman -S --noconfirm curl wget git unzip
            
            # Install PostgreSQL
            print_install "Installing PostgreSQL..."
            sudo pacman -S --noconfirm postgresql
            sudo -u postgres initdb -D /var/lib/postgres/data
            
            # Install MySQL
            print_install "Installing MySQL..."
            sudo pacman -S --noconfirm mysql
            sudo mysqld --initialize --user=mysql --basedir=/usr --datadir=/var/lib/mysql
            ;;
            
        "macos")
            # Check if Homebrew is installed
            if ! command -v brew &> /dev/null; then
                print_install "Installing Homebrew..."
                /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
            fi
            
            # Install PHP and extensions
            print_install "Installing PHP..."
            brew install php
            
            # Install PostgreSQL
            print_install "Installing PostgreSQL..."
            brew install postgresql
            
            # Install MySQL
            print_install "Installing MySQL..."
            brew install mysql
            ;;
            
        *)
            print_warning "Unknown OS. Please install PHP, PostgreSQL, and MySQL manually."
            ;;
    esac
}

# Function to install Composer
install_composer() {
    if ! command -v composer &> /dev/null; then
        print_install "Installing Composer..."
        curl -sS https://getcomposer.org/installer | php
        sleep 2
        sudo mv composer.phar /usr/local/bin/composer
        sudo chmod +x /usr/local/bin/composer
        sleep 1
        print_success "Composer installed!"
    else
        print_status "Composer already installed"
    fi
}

# Function to install Node.js via NVM
install_nodejs() {
    print_install "Installing Node.js via NVM..."
    
    # Install NVM
    if [ ! -d "$HOME/.nvm" ]; then
        curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
        sleep 3
        export NVM_DIR="$HOME/.nvm"
        [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
        [ -s "$NVM_DIR/bash_completion" ] && \. "$NVM_DIR/bash_completion"
    else
        export NVM_DIR="$HOME/.nvm"
        [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"
    fi
    
    # Install latest LTS Node.js
    nvm install --lts
    sleep 2
    nvm use --lts
    nvm alias default node
    
    print_success "Node.js installed via NVM!"
}

# Function to setup databases
setup_databases() {
    print_install "Setting up databases..."
    
    # Setup PostgreSQL
    case $OS in
        "debian"|"macos")
            if command -v systemctl &> /dev/null; then
                sudo systemctl enable postgresql
                sudo systemctl start postgresql
            elif command -v brew &> /dev/null; then
                brew services start postgresql
            fi
            ;;
        "redhat")
            sudo systemctl enable postgresql
            sudo systemctl start postgresql
            ;;
        "arch")
            sudo systemctl enable postgresql
            sudo systemctl start postgresql
            ;;
    esac
    
    # Setup MySQL
    case $OS in
        "debian")
            sudo systemctl enable mysql
            sudo systemctl start mysql
            ;;
        "redhat"|"arch")
            sudo systemctl enable mysqld
            sudo systemctl start mysqld
            ;;
        "macos")
            brew services start mysql
            ;;
    esac
    
    print_success "Database services started!"
    
    # Create databases
    print_status "Creating databases..."
    
    # PostgreSQL database
    sudo -u postgres psql -c "CREATE DATABASE ngabaca;" 2>/dev/null || true
    sudo -u postgres psql -c "CREATE USER ngabaca WITH PASSWORD 'ngabaca123';" 2>/dev/null || true
    sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE ngabaca TO ngabaca;" 2>/dev/null || true
    
    # MySQL database
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS ngabaca;" 2>/dev/null || true
    mysql -u root -e "CREATE USER IF NOT EXISTS 'ngabaca'@'localhost' IDENTIFIED BY 'ngabaca123';" 2>/dev/null || true
    mysql -u root -e "GRANT ALL PRIVILEGES ON ngabaca.* TO 'ngabaca'@'localhost';" 2>/dev/null || true
    mysql -u root -e "FLUSH PRIVILEGES;" 2>/dev/null || true
    
    print_success "Databases created!"
}

# Install system requirements
install_system_requirements

# Install Composer
install_composer

# Install Node.js
install_nodejs

# Setup databases
setup_databases

# Check if required commands exist
print_status "Verifying installations..."

if ! command -v php &> /dev/null; then
    print_error "PHP installation failed!"
    exit 1
fi

if ! command -v composer &> /dev/null; then
    print_error "Composer installation failed!"
    exit 1
fi

# Source NVM for current session
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

if ! command -v node &> /dev/null; then
    print_error "Node.js installation failed!"
    exit 1
fi

if ! command -v npm &> /dev/null; then
    print_error "NPM installation failed!"
    exit 1
fi

print_success "All requirements verified!"

# Display versions
print_status "Installed versions:"
echo "PHP: $(php --version | head -n 1)"
echo "Composer: $(composer --version)"
echo "Node.js: $(node --version)"
echo "NPM: $(npm --version)"

# Install PHP dependencies first
print_status "Installing PHP dependencies..."
if composer install; then
    print_success "PHP dependencies installed!"
    sleep 2
else
    print_error "Failed to install PHP dependencies"
    exit 1
fi

# Install Laravel Pail for log monitoring
print_status "Installing Laravel Pail..."
if composer require laravel/pail --dev; then
    print_success "Laravel Pail installed!"
    sleep 1
else
    print_warning "Failed to install Laravel Pail (might already be installed)"
fi

# Clean up any existing .env file
print_status "Cleaning up old .env file (if any)..."
# Remove existing .env file to ensure a clean decryption
rm -f .env 2>/dev/null # Use -f to force remove and suppress errors if file doesn't exist
sleep 1

# Decrypt environment file using Laravel's built-in command
print_status "Decrypting environment file..."

if [ ! -f ".env.encrypted" ]; then
    print_error ".env.encrypted file not found!"
    exit 1
fi

# Use Laravel's built-in decryption via environment variable
# Make sure the ENCRYPTION_KEY is the base64 encoded key (e.g., base64:YOUR_KEY)
if LARAVEL_ENV_ENCRYPTION_KEY="$ENCRYPTION_KEY" php artisan env:decrypt; then
    print_success "Environment file decrypted successfully!"
    sleep 1
else
    print_error "Failed to decrypt environment file. Check your encryption key or if .env.encrypted is valid!"
    print_warning "Make sure your encryption key is in format: base64:YOUR_KEY"
    exit 1
fi

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
if npm install; then
    print_success "Node.js dependencies installed!"
    sleep 2
    
    # Fix permissions for Vite binary
    print_status "Setting permissions for Vite binary..."
    if [ -f "node_modules/.bin/vite" ]; then
        chmod +x node_modules/.bin/vite
        print_success "Vite binary permissions set!"
    else
        print_warning "Vite binary not found, skipping permission fix"
    fi
    
    # Fix permissions for other common binaries
    if [ -d "node_modules/.bin" ]; then
        chmod +x node_modules/.bin/* 2>/dev/null || true
        print_success "Node.js binary permissions updated!"
        sleep 1
    fi
else
    print_error "Failed to install Node.js dependencies"
    exit 1
fi

# Generate application key
print_status "Generating application key..."
if php artisan key:generate --force; then
    print_success "Application key generated!"
    sleep 1
else
    print_warning "Failed to generate application key"
fi

# Set proper permissions
print_status "Setting file permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null
print_success "File permissions set!"
sleep 1

# Clear cache
print_status "Clearing application cache..."
php artisan cache:clear &>/dev/null
php artisan config:clear &>/dev/null
php artisan view:clear &>/dev/null
php artisan route:clear &>/dev/null
print_success "Cache cleared!"
sleep 1

# Database setup
print_status "Setting up database..."
if php artisan migrate --force; then
    print_success "Database migrated!"
    sleep 1
    
    # Ask if user wants to seed database
    read -p "Do you want to seed the database? (y/n): " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        if php artisan db:seed --force; then
            print_success "Database seeded!"
            sleep 1
        else
            print_warning "Database seeding failed"
        fi
    fi
else
    print_warning "Database migration failed. Please check your database configuration."
fi

# Storage link
print_status "Creating storage link..."
if php artisan storage:link; then
    print_success "Storage link created!"
    sleep 1
else
    print_warning "Failed to create storage link"
fi

echo ""
echo "🎉 Setup completed successfully!"
echo "=================================="
echo ""
echo "Next steps:"
echo "1. Start development server: composer run dev"
echo "2. Visit: http://localhost:8000"
echo "3. Monitor logs: php artisan pail"
echo ""
print_success "Happy coding! 🚀"