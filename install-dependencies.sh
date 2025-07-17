#!/bin/bash



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
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# checking composer installation
echo "Checking Composer installation..."
composer --version

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