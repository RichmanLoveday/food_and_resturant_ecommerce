#!/usr/bin/env bash

echo "🚀 Starting Laravel Deployment..."

# Ensure permissions are correct at runtime as a final safeguard
echo "🔧 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Check if .env exists and APP_KEY is set. If not, generate a new key.
# This prevents key generation on every restart.
if [ ! -f .env ] || ! grep -q "APP_KEY=" .env; then
  echo "🔑 Generating application key..."
  php artisan key:generate --force
fi

# Clear and cache Laravel configuration, routes, and views
echo "📦 Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "🛠️ Running migrations..."
php artisan migrate --force

# Seed the database
echo "🌱 Seeding database..."
php artisan db:seed --force

echo "✅ Deployment finished."

# The base image's entrypoint will now take over and start Nginx/PHP-FPM.