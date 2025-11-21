#!/bin/bash

# Install required PHP extensions and Node.js
apt-get update
apt-get install -y libzip-dev libpng-dev libjpeg-dev libfreetype6-dev curl git unzip netcat-traditional

# Install Node.js 18.x
curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
apt-get install -y nodejs

# Configure and install PHP extensions
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install pdo_mysql gd zip

# Install Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Change to app directory
cd /var/www/html

# Install ALL dependencies (including dev dependencies for Laravel Breeze, Spatie, etc.)
composer install --optimize-autoloader

# Install Node.js dependencies
npm install

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "MySQL is ready!"

# Setup environment
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Configure database settings in .env
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=mysql/' .env
sed -i 's/# DB_PORT=3306/DB_PORT=3306/' .env
sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=clearit_db/' .env
sed -i 's/# DB_USERNAME=root/DB_USERNAME=clearit_user/' .env
sed -i 's/# DB_PASSWORD=/DB_PASSWORD=clearit_password/' .env

# Generate key
php artisan key:generate --force

# Clear and cache configs
php artisan config:clear
php artisan cache:clear

# Wait a bit more for MySQL
sleep 5

# Run migrations with retry
max_attempts=3
attempt=1
while [ $attempt -le $max_attempts ]; do
    echo "Attempting migration (attempt $attempt/$max_attempts)..."
    if php artisan migrate --force; then
        echo "Migration successful!"
        break
    else
        echo "Migration failed, retrying in 10 seconds..."
        sleep 10
        ((attempt++))
    fi
done

# Seed database (this includes Laravel Breeze and Spatie Permission setup)
php artisan db:seed --force || echo "Seeding failed, continuing..."

# Create storage link
php artisan storage:link --force || echo "Storage link already exists"

# Build assets with Vite
npm run build

# Final permission fix
chown -R www-data:www-data /var/www/html
chmod -R 755 storage bootstrap/cache public/build

echo "Laravel setup completed successfully!"
echo "Application ready with Laravel Breeze, Spatie Permissions, and all dependencies installed!"