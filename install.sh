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

# Add PHP version check function
check_php_version() {
    print_status "Checking PHP version..."
    
    if ! command -v php &> /dev/null; then
        print_error "PHP is not installed!"
        return 1
    fi
    
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
    PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
    
    print_status "Current PHP version: $PHP_VERSION"
    
    if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
        print_error "PHP 8.2 or higher is required. Current version: $PHP_VERSION"
        print_error "The script will attempt to upgrade PHP automatically."
        return 1
    fi
    
    print_success "PHP version check passed!"
    return 0
}

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
            
            # Add Ondrej's PPA for latest PHP versions
            print_install "Adding PHP repository..."
            sudo apt install -y software-properties-common
            sudo add-apt-repository ppa:ondrej/php -y
            sudo apt update
            
            # Install PHP 8.2 and extensions
            print_install "Installing PHP 8.2 and extensions..."
            sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-json php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-pear php8.2-bcmath php8.2-pgsql php8.2-intl php8.2-soap php8.2-xmlrpc php8.2-opcache php8.2-tokenizer php8.2-fileinfo php8.2-dom php8.2-simplexml php8.2-ctype
            
            # Set PHP 8.2 as default
            print_install "Setting PHP 8.2 as default..."
            sudo update-alternatives --install /usr/bin/php php /usr/bin/php8.2 100
            sudo update-alternatives --set php /usr/bin/php8.2
            
            # Install other requirements
            sudo apt install -y curl wget git unzip apt-transport-https ca-certificates gnupg lsb-release
            
            # Install PostgreSQL
            print_install "Installing PostgreSQL..."
            sudo apt install -y postgresql postgresql-contrib
            
            # Install MySQL
            print_install "Installing MySQL..."
            sudo apt install -y mysql-server mysql-client
            ;;
            
        "redhat")
            sudo yum update -y
            
            # Install EPEL and Remi repositories
            print_install "Adding repositories..."
            sudo yum install -y epel-release
            if [ ! -f /etc/yum.repos.d/remi.repo ]; then
                sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm
            fi
            
            # Install PHP 8.2 and extensions
            print_install "Installing PHP 8.2 and extensions..."
            sudo yum module reset php -y
            sudo yum module enable php:remi-8.2 -y
            sudo yum install -y php php-cli php-fpm php-json php-common php-mysqlnd php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath php-pgsql php-intl php-soap php-xmlrpc php-opcache php-tokenizer php-fileinfo php-dom php-simplexml php-ctype
            
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
            
            # Install PHP and extensions (Arch usually has latest PHP)
            print_install "Installing PHP and extensions..."
            sudo pacman -S --noconfirm php php-fpm php-gd php-intl php-pgsql php-sqlite php-apache php-cgi php-embed php-phpdbg
            
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
                # Add Homebrew to PATH for current session
                echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile
                eval "$(/opt/homebrew/bin/brew shellenv)"
            fi
            
            # Update Homebrew
            brew update
            
            # Install PHP 8.2 and extensions
            print_install "Installing PHP 8.2..."
            brew install php@8.2
            brew link php@8.2 --force --overwrite
            
            # Add PHP to PATH
            echo 'export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"' >> ~/.zprofile
            echo 'export PATH="/opt/homebrew/opt/php@8.2/sbin:$PATH"' >> ~/.zprofile
            export PATH="/opt/homebrew/opt/php@8.2/bin:$PATH"
            export PATH="/opt/homebrew/opt/php@8.2/sbin:$PATH"
            
            # Install PostgreSQL
            print_install "Installing PostgreSQL..."
            brew install postgresql@14
            brew link postgresql@14 --force
            
            # Install MySQL
            print_install "Installing MySQL..."
            brew install mysql
            ;;
            
        *)
            print_warning "Unknown OS. Please install PHP 8.2+, PostgreSQL, and MySQL manually."
            print_warning "Required PHP extensions: cli, fpm, json, common, mysql, zip, gd, mbstring, curl, xml, bcmath, pgsql, intl, soap, opcache, tokenizer, fileinfo, dom, simplexml, ctype"
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
        # Update composer to latest version
        print_install "Updating Composer..."
        composer self-update
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
        "debian")
            if command -v systemctl &> /dev/null; then
                sudo systemctl enable postgresql
                sudo systemctl start postgresql
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
        "macos")
            if command -v brew &> /dev/null; then
                brew services start postgresql@14
            fi
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
    
    # MySQL database (try with and without password)
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS ngabaca;" 2>/dev/null || mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS ngabaca;" 2>/dev/null || true
    mysql -u root -e "CREATE USER IF NOT EXISTS 'ngabaca'@'localhost' IDENTIFIED BY 'ngabaca123';" 2>/dev/null || mysql -u root -p -e "CREATE USER IF NOT EXISTS 'ngabaca'@'localhost' IDENTIFIED BY 'ngabaca123';" 2>/dev/null || true
    mysql -u root -e "GRANT ALL PRIVILEGES ON ngabaca.* TO 'ngabaca'@'localhost';" 2>/dev/null || mysql -u root -p -e "GRANT ALL PRIVILEGES ON ngabaca.* TO 'ngabaca'@'localhost';" 2>/dev/null || true
    mysql -u root -e "FLUSH PRIVILEGES;" 2>/dev/null || mysql -u root -p -e "FLUSH PRIVILEGES;" 2>/dev/null || true
    
    print_success "Databases created!"
}

# Install system requirements first
install_system_requirements

# Check PHP version after installation
if ! check_php_version; then
    print_error "PHP version check failed after installation attempt."
    print_error "Please manually install PHP 8.2 or higher and run this script again."
    exit 1
fi

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

# Final PHP version check
final_php_check() {
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
    PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
    
    if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
        print_error "Final PHP version check failed. Version: $PHP_VERSION"
        print_error "Laravel 12 requires PHP 8.2 or higher."
        print_error "Please upgrade PHP manually and run the script again."
        exit 1
    fi
    
    print_success "Final PHP version check passed: $PHP_VERSION"
}

final_php_check

# Install PHP dependencies first
print_status "Installing PHP dependencies..."
if composer install --no-interaction --prefer-dist --optimize-autoloader; then
    print_success "PHP dependencies installed!"
    sleep 2
else
    print_error "Failed to install PHP dependencies"
    print_error "This usually means PHP version is still incompatible or missing extensions."
    print_status "Trying to install missing PHP extensions..."
    
    # Try to install common missing extensions based on OS
    case $OS in
        "debian")
            sudo apt install -y php8.2-tokenizer php8.2-fileinfo php8.2-dom php8.2-simplexml php8.2-ctype 2>/dev/null || true
            ;;
        "redhat")
            sudo yum install -y php-tokenizer php-fileinfo php-dom php-simplexml php-ctype 2>/dev/null || true
            ;;
    esac
    
    # Try composer install again
    print_status "Retrying PHP dependencies installation..."
    if composer install --no-interaction --prefer-dist --optimize-autoloader; then
        print_success "PHP dependencies installed on retry!"
    else
        print_error "Failed to install PHP dependencies on retry"
        print_error "Please check the error messages above and install missing PHP extensions manually."
        exit 1
    fi
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
    print_warning "Creating a basic .env file from .env.example..."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        print_success "Basic .env file created from .env.example"
    else
        print_error "No .env.example file found either!"
        exit 1
    fi
else
    # Use Laravel's built-in decryption via environment variable
    # Make sure the ENCRYPTION_KEY is the base64 encoded key (e.g., base64:YOUR_KEY)
    if LARAVEL_ENV_ENCRYPTION_KEY="$ENCRYPTION_KEY" php artisan env:decrypt; then
        print_success "Environment file decrypted successfully!"
        sleep 1
    else
        print_error "Failed to decrypt environment file. Check your encryption key or if .env.encrypted is valid!"
        print_warning "Make sure your encryption key is in format: base64:YOUR_KEY"
        print_warning "Falling back to .env.example..."
        if [ -f ".env.example" ]; then
            cp .env.example .env
            print_success "Basic .env file created from .env.example"
        else
            print_error "No .env.example file found either!"
            exit 1
        fi
    fi
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
if [ -d "storage" ]; then
    chmod -R 775 storage
fi
if [ -d "bootstrap/cache" ]; then
    chmod -R 775 bootstrap/cache
fi
print_success "File permissions set!"
sleep 1

# Clear cache
print_status "Clearing application cache..."
php artisan cache:clear &>/dev/null || true
php artisan config:clear &>/dev/null || true
php artisan view:clear &>/dev/null || true
php artisan route:clear &>/dev/null || true
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
    print_warning "Make sure your database is running and credentials in .env are correct."
fi

# Storage link
print_status "Creating storage link..."
if php artisan storage:link; then
    print_success "Storage link created!"
    sleep 1
else
    print_warning "Failed to create storage link (might already exist)"
fi

# Install Laravel Pail for log monitoring (optional)
print_status "Installing Laravel Pail..."
if composer require laravel/pail --dev --no-interaction; then
    print_success "Laravel Pail installed!"
    sleep 1
else
    print_warning "Failed to install Laravel Pail (might already be installed)"
fi

echo ""
echo "🎉 Setup completed successfully!"
echo "=================================="
echo ""
echo "Next steps:"
echo "1. Start development server: php artisan serve"
echo "   Or use: composer run dev (if defined in composer.json)"
echo "2. Visit: http://localhost:8000"
echo "3. Monitor logs: php artisan pail (if installed)"
echo ""
echo "For frontend development:"
echo "4. Start Vite dev server: npm run dev"
echo "5. Build for production: npm run build"
echo ""
print_success "Happy coding! 🚀"
echo ""
print_status "System Information:"
echo "OS: $OS"
echo "PHP: $(php --version | head -n 1)"
echo "Composer: $(composer --version)"
echo "Node.js: $(node --version)"
echo "NPM: $(npm --version)"
