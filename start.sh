#!/usr/bin/env bash
set -e

echo "Starting deployment setup..."

# Install composer dependencies
if [ -f /usr/local/bin/composer ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction --working-dir=/var/www/html
else
    echo "Composer not found!"
fi

# Ensure Laravel key exists
if [ ! -f /var/www/html/.env ]; then
    echo ".env file not found! Please add environment variables via Sevalla dashboard."
else
    php artisan key:generate --force || true
fi

echo "Caching Laravel config and routes..."
php artisan config:cache || true
php artisan route:cache || true

# Run migrations if DB is available
if php artisan migrate:status &>/dev/null; then
    echo "Running migrations..."
    php artisan migrate --force || true
else
    echo "Skipping migrations — DB not reachable yet."
fi

echo "Building assets..."
npm ci --silent --prefix /var/www/html || true
npm run build --prefix /var/www/html || true

echo "✅ Setup complete — starting Nginx and PHP-FPM..."
exec /start.sh
