#!/usr/bin/env bash
set -e

echo "🚀 Starting Laravel + Nginx..."

# Install dependencies (in case Sevalla clears vendor)
if [ -f /usr/local/bin/composer ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html
else
    echo "Composer not found, skipping..."
fi

# Generate key if .env exists
if [ -f /var/www/html/.env ]; then
    echo "Generating app key..."
    php artisan key:generate --force || true
    echo "Caching config and routes..."
    php artisan config:cache || true
    php artisan route:cache || true
else
    echo ".env not found — using Sevalla environment variables."
fi

echo "✅ All setup complete — starting Nginx + PHP-FPM..."
exec supervisord -n
