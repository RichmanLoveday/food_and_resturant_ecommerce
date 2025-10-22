# Use PHP + Nginx base image
FROM richarvey/nginx-php-fpm:3.1.6

# Set environment variables
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy Laravel project files
COPY . /var/www/html

# Copy custom Nginx config
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default

# Allow Git safe directory (to avoid "dubious ownership" warning)
RUN git config --global --add safe.directory /var/www/html

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Install Composer and dependencies
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer && \
    composer install --no-dev --no-scripts --optimize-autoloader --no-interaction --working-dir=/var/www/html

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Start supervisord (which runs Nginx + PHP-FPM)
CMD ["/start.sh"]
