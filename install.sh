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
    
    # Check if 'php' command exists, if not, it means PHP is not installed or not in PATH yet
    if ! command -v php &> /dev/null; then
        print_error "PHP is not installed or not found in PATH!"
        return 1
    fi
    
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
    PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
    
    print_status "Current PHP version: $PHP_VERSION"
    
    # Check for PHP 8.3 or higher (Laravel 12 requirement)
    if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 3 ]); then
        print_error "PHP 8.3 or higher is required. Current version: $PHP_VERSION"
        print_error "The script will attempt to install/upgrade PHP automatically."
        return 1
    fi
    
    print_success "PHP version check passed!"
    return 0
}

# PHP VERSION TARGET
PHP_TARGET_VERSION="8.3" # Target PHP 8.3 for Laravel 12

# Function to install system packages and PHP via apt
install_system_requirements() {
    if [ "$SKIP_SYSTEM_INSTALL" = true ]; then
        print_warning "Skipping system requirements installation"
        return
    fi

    print_install "Installing system requirements and PHP ${PHP_TARGET_VERSION} using apt..."
    
    case $OS in
        "debian")
            sudo apt update

            # Install core system tools
            print_install "Installing core system tools..."
            sudo apt install -y curl wget git unzip apt-transport-https ca-certificates gnupg lsb-release software-properties-common

            # Add Ondrej's PPA for latest PHP versions
            print_install "Adding PHP repository (Ondrej's PPA)..."
            # Ensure the key is added correctly for newer Ubuntu versions (like Noble 24.04)
            sudo mkdir -p /etc/apt/keyrings
            curl -sSL https://packages.sury.org/php/apt.gpg | sudo gpg --dearmor -o /etc/apt/keyrings/php.gpg
            # Use lsb_release -sc for dynamic codename detection
            echo "deb [signed-by=/etc/apt/keyrings/php.gpg] https://packages.sury.org/php $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list > /dev/null
            
            # Also add Ondrej's Apache2 PPA if Apache is being used for consistency, prevents conflicts
            # sudo add-apt-repository ppa:ondrej/apache2 -y # Uncomment if you explicitly need Apache from Ondrej

            # Force update, even if there are unsigned packages (useful if repo is new/changing)
            sudo apt update --allow-unauthenticated 

            # Remove any existing PHP versions and their configurations aggressively
            print_install "Removing existing PHP installations and configurations..."
            # Using a broader purge to remove conflicting older versions of php, imagick, apcu, yac
            sudo apt purge '^php[0-9]\.[0-9].*|^apache2.*' '^php-imagick$' '^php-apcu$' '^php-yac$' -y 2>/dev/null || true
            sudo apt autoremove -y # Remove orphaned packages
            sudo apt clean # Clean apt cache

            sudo apt update # Update again after purging to ensure package lists are fresh

            # Install PHP 8.3 and essential extensions for Laravel
            print_install "Installing PHP ${PHP_TARGET_VERSION} and essential extensions for Laravel..."
            # Core Laravel extensions:
            # php8.3-cli: Command Line Interface for Artisan
            # php8.3-fpm: FastCGI Process Manager for web servers (Nginx, Apache+mod_fcgid)
            # php8.3-common: Fundamental modules
            # php8.3-mysql: MySQL driver (for PDO MySQL)
            # php8.3-pgsql: PostgreSQL driver (for PDO PostgreSQL)
            # php8.3-sqlite3: SQLite driver (useful for local development/testing)
            # php8.3-zip: For Composer and archive handling
            # php8.3-gd: Image manipulation
            # php8.3-mbstring: Multibyte string support
            # php8.3-curl: HTTP client requests
            # php8.3-xml: XML parsing
            # php8.3-bcmath: Arbitrary-precision mathematics
            # php8.3-intl: Internationalization support
            # php8.3-soap: For SOAP web services (if needed)
            # php8.3-opcache: Performance enhancer (highly recommended)
            # php8.3-readline: Better CLI experience
            # libapache2-mod-php8.3: If using Apache as web server, otherwise can be skipped
            
            # Using a single line for installation for clarity
            sudo apt install -y php${PHP_TARGET_VERSION} \
                                php${PHP_TARGET_VERSION}-cli \
                                php${PHP_TARGET_VERSION}-fpm \
                                php${PHP_TARGET_VERSION}-common \
                                php${PHP_TARGET_VERSION}-mysql \
                                php${PHP_TARGET_VERSION}-pgsql \
                                php${PHP_TARGET_VERSION}-sqlite3 \
                                php${PHP_TARGET_VERSION}-zip \
                                php${PHP_TARGET_VERSION}-gd \
                                php${PHP_TARGET_VERSION}-mbstring \
                                php${PHP_TARGET_VERSION}-curl \
                                php${PHP_TARGET_VERSION}-xml \
                                php${PHP_TARGET_VERSION}-bcmath \
                                php${PHP_TARGET_VERSION}-intl \
                                php${PHP_TARGET_VERSION}-soap \
                                php${PHP_TARGET_VERSION}-opcache \
                                php${PHP_TARGET_VERSION}-readline \
                                libapache2-mod-php${PHP_TARGET_VERSION} # If using Apache
            
            if [ $? -ne 0 ]; then
                print_error "Failed to install PHP ${PHP_TARGET_VERSION} and its essential extensions via apt!"
                print_error "Please check the apt error messages above for details. You may need to manually install missing packages."
                exit 1
            fi
            
            # Set PHP 8.3 as default
            print_install "Setting PHP ${PHP_TARGET_VERSION} as default..."
            sudo update-alternatives --set php /usr/bin/php${PHP_TARGET_VERSION}
            sudo update-alternatives --set php-fpm /usr/sbin/php-fpm${PHP_TARGET_VERSION} 2>/dev/null || true # For php-fpm, if available
            print_success "PHP ${PHP_TARGET_VERSION} set as default."
            
            # Install PostgreSQL (still using apt)
            print_install "Installing PostgreSQL..."
            sudo apt install -y postgresql postgresql-contrib
            
            # Install MySQL (still using apt)
            print_install "Installing MySQL..."
            sudo apt install -y mysql-server mysql-client
            ;;
            
        "redhat")
            print_warning "RedHat/CentOS detected. This script currently only automates PHP setup for Debian/Ubuntu."
            print_warning "Please manually install PHP 8.3+, PostgreSQL, and MySQL using your system's package manager (yum/dnf)."
            print_warning "Refer to official Remi's RPM repository for PHP: https://rpms.remirepo.net/"
            exit 1 # Exit if not Debian/Ubuntu for simplified PHP installation
            ;;
            
        "arch")
            print_warning "Arch Linux detected. This script currently only automates PHP setup for Debian/Ubuntu."
            print_warning "Please manually install PHP 8.3+, PostgreSQL, and MySQL using pacman."
            exit 1 # Exit if not Debian/Ubuntu
            ;;
            
        "macos")
            print_warning "macOS detected. This script currently only automates PHP setup for Debian/Ubuntu."
            print_warning "Please manually install PHP 8.3+ via Homebrew, PostgreSQL, and MySQL."
            exit 1 # Exit if not Debian/Ubuntu
            ;;
            
        *)
            print_warning "Unknown OS. This script currently only automates PHP setup for Debian/Ubuntu."
            print_warning "Please manually install PHP 8.3+, PostgreSQL, and MySQL."
            exit 1
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
        sleep 3 # Give NVM install time to complete and set up env
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
    case $OS in # Using OS from detect_os function
        "debian") # Only proceed for Debian for automated parts
            if command -v systemctl &> /dev/null; then
                sudo systemctl enable postgresql
                sudo systemctl start postgresql
            fi
            
            print_success "Database services started!"
            
            # Create databases
            print_status "Creating databases..."
            
            # PostgreSQL database
            # Check if database exists before creating
            sudo -u postgres psql -lqt | cut -d \| -f 1 | grep -wq ngabaca
            if [ $? -ne 0 ]; then
                sudo -u postgres psql -c "CREATE DATABASE ngabaca;"
                print_status "PostgreSQL database 'ngabaca' created."
            else
                print_status "PostgreSQL database 'ngabaca' already exists, skipping creation."
            fi
            
            # Check if user exists before creating
            sudo -u postgres psql -tAc "SELECT 1 FROM pg_user WHERE usename = 'ngabaca'" | grep -q 1
            if [ $? -ne 0 ]; then
                sudo -u postgres psql -c "CREATE USER ngabaca WITH PASSWORD 'ngabaca123';"
                sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE ngabaca TO ngabaca;"
                print_status "PostgreSQL user 'ngabaca' created and granted privileges."
            else
                print_status "PostgreSQL user 'ngabaca' already exists, skipping creation."
            fi

            # MySQL database (try with and without password)
            # Check if database exists
            mysql -u root -e "USE ngabaca;" 2>/dev/null
            if [ $? -ne 0 ]; then
                mysql -u root -e "CREATE DATABASE IF NOT EXISTS ngabaca;" || mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS ngabaca;"
                print_status "MySQL database 'ngabaca' created."
            else
                print_status "MySQL database 'ngabaca' already exists, skipping creation."
            fi 
            
            # Check if user exists
            mysql -u root -e "SELECT User FROM mysql.user WHERE User='ngabaca';" 2>/dev/null | grep -q ngabaca
            if [ $? -ne 0 ]; then
                mysql -u root -e "CREATE USER IF NOT EXISTS 'ngabaca'@'localhost' IDENTIFIED BY 'ngabaca123';" || mysql -u root -p -e "CREATE USER IF NOT EXISTS 'ngabaca'@'localhost' IDENTIFIED BY 'ngabaca123';"
                mysql -u root -e "GRANT ALL PRIVILEGES ON ngabaca.* TO 'ngabaca'@'localhost';" || mysql -u root -p -e "GRANT ALL PRIVILEGES ON ngabaca.* TO 'ngabaca'@'localhost';"
                mysql -u root -e "FLUSH PRIVILEGES;" || mysql -u root -p -e "FLUSH PRIVILEGES;"
                print_status "MySQL user 'ngabaca' created and granted privileges."
            else
                print_status "MySQL user 'ngabaca' already exists, skipping creation."
            fi
            
            print_success "Databases creation/check completed!"
            ;;
        *)
            print_warning "Database setup is not automated for your OS. Please configure PostgreSQL and MySQL manually."
            ;;
    esac
}

# Install system requirements first
install_system_requirements

# Check PHP version after installation
if ! check_php_version; then
    print_error "PHP version check failed after installation attempt."
    print_error "Please manually troubleshoot PHP installation and run this script again."
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
echo "PHP: $(php --version 2>/dev/null | head -n 1 || echo 'Not installed')"
echo "Composer: $(composer --version 2>/dev/null | head -n 1 || echo 'Not installed')"
echo "Node.js: $(node --version 2>/dev/null | head -n 1 || echo 'Not installed')"
echo "NPM: $(npm --version 2>/dev/null | head -n 1 || echo 'Not installed')"

# Final PHP version check
final_php_check() {
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
    PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")
    
    # Laravel 12 requires PHP 8.2 or higher
    if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
        print_error "Final PHP version check failed. Version: ${PHP_VERSION}"
        print_error "Your Laravel project requires PHP 8.2 or higher."
        print_error "Please upgrade PHP manually and run the script again."
        exit 1
    fi
    
    print_success "Final PHP version check passed: ${PHP_VERSION}"
}

final_php_check

# Install PHP dependencies first
print_status "Installing PHP dependencies (Composer)..."
if composer install --no-interaction --prefer-dist --optimize-autoloader; then
    print_success "PHP dependencies installed!"
    sleep 2
else
    print_error "Failed to install PHP dependencies with Composer."
    print_error "This usually means PHP version is still incompatible or missing extensions."
    print_error "Please ensure all required PHP extensions are installed and try running 'composer install' manually."
    exit 1
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
        print_error "No .env.example file found either! Cannot proceed without .env file."
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
            print_error "No .env.example file found either! Cannot proceed without .env file."
            exit 1
        fi
    fi
fi

# Install Node.js dependencies
print_status "Installing Node.js dependencies (npm)..."
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
        chmod +x node_modules/.bin/* 2>/dev/null || true # Suppress errors for non-executable files
        print_success "Node.js binary permissions updated!"
        sleep 1
    fi
else
    print_error "Failed to install Node.js dependencies."
    print_error "Please check NPM error output and try running 'npm install' manually."
    exit 1
fi

# Generate application key
print_status "Generating application key..."
# Check if APP_KEY already exists and is not empty before generating
if ! grep -q "^APP_KEY=.\+$" .env; then
    if php artisan key:generate --force; then
        print_success "Application key generated!"
        sleep 1
    else
        print_warning "Failed to generate application key. Manual generation might be needed (php artisan key:generate)."
    fi
else
    print_status "Application key already exists, skipping generation."
fi


# --- Fix Permissions for Laravel Storage ---
print_status "Setting file permissions for Laravel storage and cache..."

# Determine the web server user based on OS (most common is www-data for Debian/Ubuntu)
WEB_SERVER_USER="www-data"
if [ "$OS" = "redhat" ]; then
    WEB_SERVER_USER="apache"
elif [ "$OS" = "arch" ]; then
    WEB_SERVER_USER="http"
fi

# Ensure ACL package is installed (required for setfacl)
print_install "Installing ACL package..."
sudo apt install -y acl
sleep 1

# Check if the web server user exists before trying to chown/setfacl to it
if id -u "$WEB_SERVER_USER" >/dev/null 2>&1; then
    # 1. Set the owner to the current user and group to the web server user
    # This ensures both the current user and the web server group have appropriate access.
    # The current user will be $(whoami)
    CURRENT_USER=$(whoami)
    sudo chown -R "$CURRENT_USER":"$WEB_SERVER_USER" ~/ngabaca/storage ~/ngabaca/bootstrap/cache
    print_status "Ownership set to $CURRENT_USER:$WEB_SERVER_USER."
    sleep 1

    # 2. Set basic read/write/execute permissions for owner and group
    sudo chmod -R 775 ~/ngabaca/storage ~/ngabaca/bootstrap/cache
    print_status "Basic permissions set to 775."
    sleep 1

    # 3. Apply Access Control Lists (ACL) for finer-grained control and persistence
    # -R: recursive
    # -m: modify ACL
    # u:WEB_SERVER_USER:rwX: gives web server user read, write, and execute (for directories)
    # u:CURRENT_USER:rwX: gives current user read, write, and execute (for directories)
    # -d: default ACL (for future created files/directories)
    sudo setfacl -R -m u:"$WEB_SERVER_USER":rwX -m u:"$CURRENT_USER":rwX ~/ngabaca/storage ~/ngabaca/bootstrap/cache
    sudo setfacl -dR -m u:"$WEB_SERVER_USER":rwX -m u:"$CURRENT_USER":rwX ~/ngabaca/storage ~/ngabaca/bootstrap/cache
    print_success "ACL permissions set for persistent access."
    sleep 1
else
    print_warning "Web server user '$WEB_SERVER_USER' not found. Skipping advanced permission setup."
    print_warning "Please ensure permissions for ~/ngabaca/storage and ~/ngabaca/bootstrap/cache are correctly set manually."
    # Fallback to basic chmod for current user if web server user is not found
    sudo chmod -R 775 ~/ngabaca/storage ~/ngabaca/bootstrap/cache
    print_success "Basic permissions set for current user."
    sleep 1
fi

print_success "File permissions set successfully!"
# --- End Fix Permissions ---


# Clear cache
print_status "Clearing application cache..."
php artisan cache:clear &>/dev/null || true
php artisan config:clear &>/dev/null || true
php artisan view:clear &>/dev/null || true
php artisan route:clear &>/dev/null || true
print_success "Cache cleared!"
sleep 1

# Database setup
print_status "Setting up database migrations..."
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
            print_warning "Database seeding failed. Check your seeder files or database connection."
        fi
    fi
else
    print_warning "Database migration failed. Please check your database configuration in .env and ensure database services are running."
fi

# Storage link
print_status "Creating storage link..."
if php artisan storage:link; then
    print_success "Storage link created!"
    sleep 1
else
    print_warning "Failed to create storage link (might already exist, or storage directory permissions are incorrect)."
fi

# Install Laravel Pail for log monitoring (optional)
print_status "Installing Laravel Pail..."
if composer require laravel/pail --dev --no-interaction; then
    print_success "Laravel Pail installed!"
    sleep 1
else
    print_warning "Failed to install Laravel Pail (might already be installed or Composer issue)."
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
echo "PHP: $(php --version 2>/dev/null | head -n 1 || echo 'Not installed')"
echo "Composer: $(composer --version 2>/dev/null | head -n 1 || echo 'Not installed')"
echo "Node.js: $(node --version 2>/dev/null | head -n 1 || echo 'Not installed')"
echo "NPM: $(npm --version 2>/dev/null | head -n 1 || echo 'Not installed')"
