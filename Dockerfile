# Use PHP + Nginx base image
FROM richarvey/nginx-php-fpm:3.1.6

# Set environment variables
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy Laravel project files
COPY . /var/www/html

# Copy custom Nginx config
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ Install Composer dependencies (without running artisan commands)
RUN composer install --no-dev --no-scripts --working-dir=/var/www/html --prefer-dist --no-interaction

# ✅ Copy your start script (entrypoint)
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start container (Nginx + PHP-FPM + your start.sh)
CMD ["/start.sh"]
