FROM richarvey/nginx-php-fpm:3.1.6

# Copy all project files
COPY . .

# Laravel environment variables
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set custom Nginx config
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Permissions (important for storage/logs and cache)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Production optimizations (Laravel cache)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose the dynamic port (Sevalla assigns PORT automatically)
EXPOSE ${PORT}

# Start script
CMD ["/start.sh"]
