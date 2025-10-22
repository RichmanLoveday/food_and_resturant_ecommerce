# Use PHP + Nginx base image
FROM richarvey/nginx-php-fpm:3.1.6

# Environment setup
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy project files
COPY . /var/www/html

# Custom Nginx config
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Safe git permissions
RUN git config --global --add safe.directory /var/www/html

# Fix permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Install dependencies (no artisan commands yet)
RUN composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --working-dir=/var/www/html

# Copy the startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose Sevalla’s dynamic port (important)
EXPOSE ${PORT}

# Start using the base image’s process manager
CMD ["/start.sh"]
