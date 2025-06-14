#!/bin/bash

# Ngabaca Project Setup Script (Manual Installation Guide)
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

print_requirement() {
    echo -e "${PURPLE}[REQUIRED]${NC} $1"
}

# Check if encryption key is provided
if [ $# -eq 0 ]; then
    print_error "Encryption key required!"
    echo "Usage: ./install.sh YOUR_ENCRYPTION_KEY"
    exit 1
fi

ENCRYPTION_KEY=$1

echo "🚀 Ngabaca Project Setup (Manual Installation Guide)"
echo "===================================================="

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

# Function to check requirements silently
check_php() {
    command -v php &> /dev/null && php -r "exit(PHP_MAJOR_VERSION >= 8 && PHP_MINOR_VERSION >= 3 ? 0 : 1);" 2>/dev/null
}

check_composer() {
    command -v composer &> /dev/null
}

check_node() {
    command -v node &> /dev/null
}

check_npm() {
    command -v npm &> /dev/null
}

check_postgresql() {
    command -v psql &> /dev/null || systemctl is-active postgresql &> /dev/null
}

check_mysql() {
    command -v mysql &> /dev/null || systemctl is-active mysql &> /dev/null
}

# Function to show installation instructions
show_installation_instructions() {
    echo ""
    echo "📋 MANUAL INSTALLATION REQUIRED"
    echo "================================"
    echo ""
    
    case $OS in
        "debian")
            echo "🐧 Debian/Ubuntu Installation Commands:"
            echo "--------------------------------------"
            
            if ! check_php; then
                print_requirement "PHP 8.3+ installation:"
                echo "sudo apt update"
                echo "sudo apt install -y software-properties-common"
                echo "sudo add-apt-repository ppa:ondrej/php -y"
                echo "sudo apt update"
                echo "sudo apt install -y php8.3 php8.3-cli php8.3-fpm php8.3-mysql php8.3-pgsql php8.3-sqlite3 php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-intl php8.3-opcache"
                echo ""
            fi
            
            if ! check_composer; then
                print_requirement "Composer installation:"
                echo "curl -sS https://getcomposer.org/installer | php"
                echo "sudo mv composer.phar /usr/local/bin/composer"
                echo "sudo chmod +x /usr/local/bin/composer"
                echo ""
            fi
            
            if ! check_node; then
                print_requirement "Node.js installation (via NVM):"
                echo "curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash"
                echo "source ~/.bashrc"
                echo "nvm install --lts"
                echo "nvm use --lts"
                echo ""
            fi
            
            if ! check_postgresql; then
                print_requirement "PostgreSQL installation:"
                echo "sudo apt install -y postgresql postgresql-contrib"
                echo "sudo systemctl enable postgresql"
                echo "sudo systemctl start postgresql"
                echo ""
            fi
            
            if ! check_mysql; then
                print_requirement "MySQL installation:"
                echo "sudo apt install -y mysql-server mysql-client"
                echo "sudo systemctl enable mysql"
                echo "sudo systemctl start mysql"
                echo ""
            fi
            ;;
            
        "redhat")
            echo "🎩 RedHat/CentOS/Fedora Installation Commands:"
            echo "----------------------------------------------"
            
            if ! check_php; then
                print_requirement "PHP 8.3+ installation:"
                echo "sudo dnf install -y epel-release"
                echo "sudo dnf install -y https://rpms.remirepo.net/enterprise/remi-release-8.rpm"
                echo "sudo dnf module reset php"
                echo "sudo dnf module enable php:remi-8.3"
                echo "sudo dnf install -y php php-cli php-fpm php-mysql php-pgsql php-sqlite3 php-zip php-gd php-mbstring php-curl php-xml php-bcmath php-intl php-opcache"
                echo ""
            fi
            
            if ! check_composer; then
                print_requirement "Composer installation:"
                echo "curl -sS https://getcomposer.org/installer | php"
                echo "sudo mv composer.phar /usr/local/bin/composer"
                echo "sudo chmod +x /usr/local/bin/composer"
                echo ""
            fi
            
            if ! check_node; then
                print_requirement "Node.js installation:"
                echo "curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash"
                echo "source ~/.bashrc"
                echo "nvm install --lts"
                echo ""
            fi
            
            if ! check_postgresql; then
                print_requirement "PostgreSQL installation:"
                echo "sudo dnf install -y postgresql postgresql-server postgresql-contrib"
                echo "sudo postgresql-setup --initdb"
                echo "sudo systemctl enable postgresql"
                echo "sudo systemctl start postgresql"
                echo ""
            fi
            
            if ! check_mysql; then
                print_requirement "MySQL installation:"
                echo "sudo dnf install -y mysql-server"
                echo "sudo systemctl enable mysqld"
                echo "sudo systemctl start mysqld"
                echo ""
            fi
            ;;
            
        "arch")
            echo "🏛️ Arch Linux Installation Commands:"
            echo "-----------------------------------"
            
            if ! check_php; then
                print_requirement "PHP 8.3+ installation:"
                echo "sudo pacman -S php php-fpm php-sqlite php-gd php-intl php-pgsql"
                echo ""
            fi
            
            if ! check_composer; then
                print_requirement "Composer installation:"
                echo "sudo pacman -S composer"
                echo ""
            fi
            
            if ! check_node; then
                print_requirement "Node.js installation:"
                echo "sudo pacman -S nodejs npm"
                echo ""
            fi
            
            if ! check_postgresql; then
                print_requirement "PostgreSQL installation:"
                echo "sudo pacman -S postgresql"
                echo "sudo -u postgres initdb -D /var/lib/postgres/data"
                echo "sudo systemctl enable postgresql"
                echo "sudo systemctl start postgresql"
                echo ""
            fi
            
            if ! check_mysql; then
                print_requirement "MySQL installation:"
                echo "sudo pacman -S mysql"
                echo "sudo systemctl enable mysqld"
                echo "sudo systemctl start mysqld"
                echo ""
            fi
            ;;
            
        "macos")
            echo "🍎 macOS Installation Commands:"
            echo "------------------------------"
            
            echo "First install Homebrew if not already installed:"
            echo '/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"'
            echo ""
            
            if ! check_php; then
                print_requirement "PHP 8.3+ installation:"
                echo "brew install php@8.3"
                echo "brew link php@8.3 --force"
                echo ""
            fi
            
            if ! check_composer; then
                print_requirement "Composer installation:"
                echo "brew install composer"
                echo ""
            fi
            
            if ! check_node; then
                print_requirement "Node.js installation:"
                echo "brew install node"
                echo ""
            fi
            
            if ! check_postgresql; then
                print_requirement "PostgreSQL installation:"
                echo "brew install postgresql"
                echo "brew services start postgresql"
                echo ""
            fi
            
            if ! check_mysql; then
                print_requirement "MySQL installation:"
                echo "brew install mysql"
                echo "brew services start mysql"
                echo ""
            fi
            ;;
            
        *)
            echo "❓ Unknown OS - Please install manually:"
            echo "--------------------------------------"
            echo "- PHP 8.3+ with extensions: cli, fpm, mysql, pgsql, sqlite3, zip, gd, mbstring, curl, xml, bcmath, intl, opcache"
            echo "- Composer"
            echo "- Node.js (LTS) and npm"
            echo "- PostgreSQL"
            echo "- MySQL"
            echo ""
            ;;
    esac
    
    echo "🗄️ DATABASE SETUP COMMANDS:"
    echo "--------------------------"
    echo "After installing PostgreSQL:"
    echo "sudo -u postgres psql -c \"CREATE DATABASE ngabaca;\""
    echo "sudo -u postgres psql -c \"CREATE USER ngabaca WITH PASSWORD 'ngabaca123';\""
    echo "sudo -u postgres psql -c \"GRANT ALL PRIVILEGES ON DATABASE ngabaca TO ngabaca;\""
    echo ""
    echo "After installing MySQL:"
    echo "mysql -u root -e \"CREATE DATABASE ngabaca;\""
    echo "mysql -u root -e \"CREATE USER 'ngabaca'@'localhost' IDENTIFIED BY 'ngabaca123';\""
    echo "mysql -u root -e \"GRANT ALL PRIVILEGES ON ngabaca.* TO 'ngabaca'@'localhost';\""
    echo "mysql -u root -e \"FLUSH PRIVILEGES;\""
    echo ""
}

# Function to check all requirements
check_requirements() {
    local missing_requirements=()
    
    if ! check_php; then
        missing_requirements+=("PHP 8.3+")
    fi
    
    if ! check_composer; then
        missing_requirements+=("Composer")
    fi
    
    if ! check_node; then
        missing_requirements+=("Node.js")
    fi
    
    if ! check_npm; then
        missing_requirements+=("npm")
    fi
    
    if ! check_postgresql; then
        missing_requirements+=("PostgreSQL")
    fi
    
    if ! check_mysql; then
        missing_requirements+=("MySQL")
    fi
    
    if [ ${#missing_requirements[@]} -gt 0 ]; then
        print_error "Missing requirements: ${missing_requirements[*]}"
        return 1
    fi
    
    return 0
}

# Check requirements
print_status "Checking system requirements..."

if ! check_requirements; then
    show_installation_instructions
    echo ""
    print_warning "Please install the missing requirements above, then run this script again."
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

if [ -f ".env.encrypted" ]; then
    if LARAVEL_ENV_ENCRYPTION_KEY="$ENCRYPTION_KEY" php artisan env:decrypt > /dev/null 2>&1; then
        print_success "Environment file decrypted successfully!"
    else
        print_warning "Failed to decrypt environment file. Using .env.example as fallback."
        if [ -f ".env.example" ]; then
            cp .env.example .env
            print_success "Environment file created from .env.example"
        else
            print_error "No .env.example file found!"
            exit 1
        fi
    fi
else
    print_warning ".env.encrypted not found. Using .env.example."
    if [ -f ".env.example" ]; then
        cp .env.example .env
        print_success "Environment file created from .env.example"
    else
        print_error "No .env.example file found!"
        exit 1
    fi
fi

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
case $OS in
    "debian")
        sudo chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
        sudo chmod -R 775 storage bootstrap/cache 2>/dev/null || true
        ;;
    "redhat")
        sudo chown -R apache:apache storage bootstrap/cache 2>/dev/null || true
        sudo chmod -R 775 storage bootstrap/cache 2>/dev/null || true
        ;;
    "arch")
        sudo chown -R http:http storage bootstrap/cache 2>/dev/null || true
        sudo chmod -R 775 storage bootstrap/cache 2>/dev/null || true
        ;;
    "macos")
        chmod -R 775 storage bootstrap/cache 2>/dev/null || true
        ;;
esac
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