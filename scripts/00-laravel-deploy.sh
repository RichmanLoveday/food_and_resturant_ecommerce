#!/usr/bin/env bash
set -e

echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "Generating application key..."
php artisan key:generate

echo "Caching config & routes..."
php artisan config:cache
php artisan route:cache

echo "Running migrations..."
php artisan migrate:fresh --seed

echo "Creating storage symlink..."
php artisan storage:link

echo "Building frontend assets..."
npm install
npm run prod

# Replace __PORT__ in Nginx config with Render dynamic $PORT
echo "Setting dynamic port..."
envsubst '$PORT' < /etc/nginx/sites-available/default > /etc/nginx/sites-available/default.tmp
mv /etc/nginx/sites-available/default.tmp /etc/nginx/sites-available/default

# Start PHP-FPM in background
php-fpm &

# Start Nginx in foreground
nginx -g "daemon off;"
