#!/bin/bash

set -e  

echo "Starting installation process..."

# Update system packages
echo "Updating system packages..."
sudo apt update 

# Add PHP 8.4 repository
echo "Adding PHP 8.4 repository..."
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.4 and extensions
echo "Installing PHP 8.4 and extensions..."
sudo apt install -y \
    php8.4 \
    php8.4-fpm \
    php8.4-cli \
    php8.4-common \
    php8.4-pgsql \

# Install PostgreSQL CLI
echo "Installing PostgreSQL CLI..."
sudo apt install -y postgresql-client

# Install NVM (Node Version Manager)
echo "Installing NVM..."
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.3/install.sh | bash

# Install Composer
echo "Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Nginx
echo "Installing Nginx..."
sudo apt install -y nginx

# Start and enable services
echo "Starting and enabling services..."
sudo systemctl start php8.4-fpm
sudo systemctl enable php8.4-fpm
sudo systemctl start nginx
sudo systemctl enable nginx

# Check installation status
echo "Checking installation status..."
php -v
composer --version
nginx -v
psql --version

echo "Installation completed successfully!"
echo "PHP-FPM is running on: $(systemctl is-active php8.4-fpm)"
echo "Nginx is running on: $(systemctl is-active nginx)"
echo "Please reload your shell or run 'source ~/.bashrc' to use NVM"