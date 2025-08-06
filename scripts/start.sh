#!/usr/bin/env bash

echo "🚀 Starting Laravel Deployment..."

# Fix permissions
echo "🔧 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run Composer
echo "📦 Running Composer..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html


# Laravel key
echo "🔑 Generating application key..."
php artisan key:generate --force

# Laravel caches
echo "🧼 Dumping autoload..."
composer dump-autoload --optimize

echo "📦 Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrate DB
echo "🛠️ Running migrations..."
php artisan migrate --force

# Seed DB
echo "🌱 Seeding database..."
php artisan db:seed --force
