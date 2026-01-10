FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

# Copy all files
COPY . .

# Ensure bootstrap/cache exists
RUN mkdir -p bootstrap/cache && chmod -R 775 bootstrap/cache

# Install composer dependencies (skip scripts to avoid DB issues)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Set environment variables
ENV WEBROOT=/var/www/html/public \
    PHP_ERRORS_STDERR=1 \
    RUN_SCRIPTS=1 \
    REAL_IP_HEADER=1 \
    APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    COMPOSER_ALLOW_SUPERUSER=1

# Ensure storage symlink exists
RUN php artisan storage:link || true

EXPOSE 80

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

CMD ["/start.sh"]

