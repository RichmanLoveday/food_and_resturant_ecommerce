FROM richarvey/nginx-php-fpm:3.1.6

# Copy app files into container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Set environment variables
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Increase PHP memory limit to 512M
RUN echo "php_admin_value[memory_limit] = 512M" >> /etc/php/8.2/fpm/php-fpm.conf \
 && echo "memory_limit=512M" > /etc/php/8.2/fpm/conf.d/99-memory-limit.ini \
 && echo "memory_limit=512M" > /etc/php/8.2/cli/conf.d/99-memory-limit.ini

# Install Composer dependencies if needed
RUN composer install --no-dev --optimize-autoloader

# Give proper permissions
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["/start.sh"]
