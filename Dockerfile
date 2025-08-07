# Multi-stage build for a smaller, more secure final image

# Stage 1: Build the application with Composer
# Use the official PHP 8.3 CLI image as the base.
# This guarantees that your build environment is running PHP 8.3.
FROM php:8.3-cli AS composer_builder

# Install Composer on top of the PHP image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set the working directory inside the container
WORKDIR /app

# Copy only composer.json to leverage Docker's caching
# We intentionally omit composer.lock to force a fresh install.
COPY composer.json ./

# Run composer update to generate a new composer.lock file from scratch.
# We are adding the `-v` flag here to get a detailed error message in the logs.
# This is the most critical step to debug the "exit code 2" error.
RUN composer update --no-dev --optimize-autoloader -v

# Now, copy the rest of the application source code
COPY . .

# Stage 2: Final production image
# We use a known-to-exist, stable tag for the richarvey image.
# NOTE: The `3.1.6` tag is based on PHP 8.2. If your application code is not
# compatible with PHP 8.2, you will get runtime errors.
# If this happens, you will need to find a different base image that supports PHP 8.3.
FROM richarvey/nginx-php-fpm:3.1.6

# Set working directory inside the container
WORKDIR /var/www/html

# Copy application files from the builder stage
COPY --from=composer_builder /app .

# Correct the path for the Nginx configuration file
COPY conf/nginx/nginx-site.conf /etc/nginx/sites-available/default.conf

# Set environment variables for Laravel and Nginx
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Ensure the storage directory exists and has the correct permissions
# This is a critical step to prevent "Permission denied" errors
RUN mkdir -p storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy the start script
COPY scripts/start.sh /etc/startup.d/00-laravel-deploy.sh
RUN chmod +x /etc/startup.d/00-laravel-deploy.sh

# Let the base image handle startup via its entrypoint
CMD ["/start.sh"]