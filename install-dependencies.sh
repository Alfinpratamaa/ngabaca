#!/bin/bash

echo "Starting installation process..."

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check if a package is installed
package_installed() {
    dpkg -l | grep -q "^ii  $1 "
}

# Function to check if a service is active
service_active() {
    systemctl is-active --quiet "$1"
}

# Check current installation status
echo "Checking current installation status..."

# Check PHP 8.4
PHP_INSTALLED=false
if command_exists php && php -v | grep -q "PHP 8.4"; then
    echo "✓ PHP 8.4 is already installed"
    PHP_INSTALLED=true
else
    echo "✗ PHP 8.4 not found"
fi

# Check PHP-FPM 8.4
PHP_FPM_INSTALLED=false
if package_installed "php8.4-fpm"; then
    echo "✓ PHP 8.4-FPM is already installed"
    PHP_FPM_INSTALLED=true
else
    echo "✗ PHP 8.4-FPM not found"
fi

# Check PostgreSQL client
PSQL_INSTALLED=false
if command_exists psql; then
    echo "✓ PostgreSQL client is already installed"
    PSQL_INSTALLED=true
else
    echo "✗ PostgreSQL client not found"
fi

# Check NVM
NVM_INSTALLED=false
if [ -s "$HOME/.nvm/nvm.sh" ]; then
    echo "✓ NVM is already installed"
    NVM_INSTALLED=true
else
    echo "✗ NVM not found"
fi

# Check Composer
COMPOSER_INSTALLED=false
if command_exists composer; then
    echo "✓ Composer is already installed"
    COMPOSER_INSTALLED=true
else
    echo "✗ Composer not found"
fi

# Check Nginx
NGINX_INSTALLED=false
if command_exists nginx; then
    echo "✓ Nginx is already installed"
    NGINX_INSTALLED=true
else
    echo "✗ Nginx not found"
fi

echo ""

# Check if all components are already installed
if $PHP_INSTALLED && $PHP_FPM_INSTALLED && $PSQL_INSTALLED && $NVM_INSTALLED && $COMPOSER_INSTALLED && $NGINX_INSTALLED; then
    echo "All required components are already installed!"
    echo "Checking services status..."
    
    # Check services
    if service_active php8.4-fpm; then
        echo "✓ PHP-FPM is running"
    else
        echo "Starting PHP-FPM..."
        sudo systemctl start php8.4-fpm
        sudo systemctl enable php8.4-fpm
    fi
    
    if service_active nginx; then
        echo "✓ Nginx is running"
    else
        echo "Starting Nginx..."
        sudo systemctl start nginx
        sudo systemctl enable nginx
    fi
    
    echo "All components are installed and running!"
    exit 0
fi

echo "Proceeding with installation of missing components..."

# Update system packages
echo "Updating system packages..."
sudo apt update 

# Install PHP 8.4 if not installed
if ! $PHP_INSTALLED || ! $PHP_FPM_INSTALLED; then
    echo "Adding PHP 8.4 repository..."
    sudo apt install -y software-properties-common
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt update

    echo "Installing PHP 8.4 and extensions..."
    sudo apt install -y \
        php8.4 \
        php8.4-fpm \
        php8.4-cli \
        php8.4-common \
        php8.4-pgsql \
        php8.4-xml \
else
    # Check and install missing PHP extensions even if PHP is already installed
    echo "Checking PHP extensions..."
    
    EXTENSIONS=(
        "php8.4-fpm"
        "php8.4-cli" 
        "php8.4-common"
        "php8.4-pgsql"
        "php8.4-xml"
    )
    
    MISSING_EXTENSIONS=()
    
    for ext in "${EXTENSIONS[@]}"; do
        if ! package_installed "$ext"; then
            echo "✗ $ext not installed"
            MISSING_EXTENSIONS+=("$ext")
        else
            echo "✓ $ext is installed"
        fi
    done
    
    if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
        echo "Installing missing PHP extensions..."
        sudo apt install -y "${MISSING_EXTENSIONS[@]}"
    else
        echo "All PHP extensions are already installed"
    fi
fi

# Install PostgreSQL CLI if not installed
if ! $PSQL_INSTALLED; then
    echo "Installing PostgreSQL CLI..."
    sudo apt install -y postgresql-client
fi

# Install NVM if not installed
if ! $NVM_INSTALLED; then
    echo "Installing NVM..."
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash
fi

# Install Composer if not installed
if ! $COMPOSER_INSTALLED; then
    echo "Installing Composer..."
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    
    echo "Checking Composer installation..."
    composer --version
fi

# Install Nginx if not installed
if ! $NGINX_INSTALLED; then
    echo "Installing Nginx..."
    sudo apt install -y nginx
fi

# Start and enable services
echo "Starting and enabling services..."
sudo systemctl start php8.4-fpm
sudo systemctl enable php8.4-fpm
sudo systemctl start nginx
sudo systemctl enable nginx

# Final check
echo "Checking final installation status..."
php -v
composer --version
nginx -v
psql --version

echo "Installation completed successfully!"
echo "PHP-FPM is running on: $(systemctl is-active php8.4-fpm)"
echo "Nginx is running on: $(systemctl is-active nginx)"
if ! $NVM_INSTALLED; then
    echo "Please reload your shell or run 'source ~/.bashrc' to use NVM"
fi