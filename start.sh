#!/usr/bin/env bash
set -e

echo "🚀 Starting Laravel + Nginx setup..."

# Composer dependencies
if [ -f /usr/local/bin/composer ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html || true
else
    echo "Composer not found, skipping..."
fi

# Check .env
if [ -f /var/www/html/.env ]; then
    echo "Generating Laravel app key..."
    php artisan key:generate --force || true

    echo "Caching config and routes..."
    php artisan config:cache || true
    php artisan route:cache || true
else
    echo ".env not found — skipping artisan commands."
fi

echo "✅ Laravel setup complete — starting Nginx + PHP-FPM..."
exec /start.sh    # Use base image startup
