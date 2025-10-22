#!/usr/bin/env bash
set -e

echo "🚀 Starting Laravel + Nginx..."

if [ -f /usr/local/bin/composer ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html
fi

if [ -f /var/www/html/.env ]; then
    php artisan key:generate --force || true
    php artisan config:cache || true
    php artisan route:cache || true
else
    echo ".env not found — skipping artisan cache"
fi

echo "✅ Setup complete — handing control to image’s startup"
exec /start.sh    # ✅ this calls the built-in Nginx + PHP-FPM launcher
