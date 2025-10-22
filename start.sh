#!/usr/bin/env bash
set -e

echo "Starting Laravel + Nginx..."

# Install Composer deps if missing
if [ -f /usr/local/bin/composer ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html
else
    echo "Composer not found, skipping..."
fi

# Only generate key if .env exists
if [ -f /var/www/html/.env ]; then
    echo "Generating app key..."
    php artisan key:generate --force || true
else
    echo ".env not found — using Sevalla environment variables."
fi

# Skip config and route caching if .env is missing
if [ -f /var/www/html/.env ]; then
    echo "Caching Laravel config and routes..."
    php artisan config:cache || true
    php artisan route:cache || true
else
    echo "Skipping caching due to missing .env"
fi

echo "✅ Ready — launching Nginx + PHP-FPM"

# Start the original base image process
exec /start.sh.default
