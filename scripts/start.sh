#!/usr/bin/env bash

echo "🚀 Starting Laravel Deployment..."

# Ensure permissions are correct at runtime
# This is a critical step to prevent permission errors
echo "🔧 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate application key if it doesn't exist
if [ ! -f .env ] || ! grep -q "APP_KEY=" .env; then
  echo "🔑 Generating application key..."
  php artisan key:generate --force
fi

# Run caching commands
echo "📦 Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🛠️ Running migrations..."
php artisan migrate --force

# Seed the database
echo "🌱 Seeding database..."
php artisan db:seed --force

echo "✅ Deployment finished."

# The base image's entrypoint will continue the process
# We don't need `exec` here because the parent script takes over