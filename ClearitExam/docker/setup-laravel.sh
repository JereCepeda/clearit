#!/bin/bash

# Install required PHP extensions
apt-get update
apt-get install -y libzip-dev libpng-dev libjpeg-dev libfreetype6-dev curl git unzip

# Configure and install PHP extensions
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install pdo_mysql gd zip

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change to app directory
cd /var/www/html

# Install dependencies
composer install --no-dev --optimize-autoloader

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate key if not exists
if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate --force
php artisan config:cache

# Run migrations
php artisan migrate --force
php artisan db:seed --force

echo "Laravel setup completed!"

# Start PHP-FPM
php-fpm