#!/usr/bin/env bash

echo "🔧 Installing dependencies..."
composer install --no-dev --prefer-dist --working-dir=/var/www/html

echo "📦 Caching config..."
php artisan config:cache

echo "🧭 Caching routes..."
php artisan route:cache

echo "🧱 Running migrations..."
php artisan migrate --force

echo "🌱 Running seeders..."
php artisan db:seed --force
