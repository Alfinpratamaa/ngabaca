FROM php:8.2-fpm-alpine

RUN apk  add --no-cache \
    nginx \
    nodejs \
    npm \
    git \
    supervisor \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    postgresql-dev \
    mysql-client \
    imagemagick

RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql bcmath opcache zip gd exif 
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Salin file package.json terlebih dahulu untuk caching
COPY package*.json ./
# Instal dependensi Node.js
RUN npm install

COPY . .
RUN composer install --no-dev --optimize-autoloader

RUN php artisan optimize

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]