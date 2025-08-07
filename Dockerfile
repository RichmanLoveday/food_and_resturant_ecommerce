# Multi-stage build for a smaller, more secure final image

# Stage 1: Build the application with Composer
# We use a composer image with PHP 8.3, as it's the standard for Laravel 11.
FROM composer:2.7-php8.3 AS composer_builder

# Set the working directory inside the container
WORKDIR /app

# Copy only composer.json to leverage Docker's caching
# We intentionally omit composer.lock to force a fresh install.
COPY composer.json ./

# Run composer update to generate a new composer.lock file from scratch
# This ensures all dependencies are compatible with the container's environment.
# We also use --no-dev and --optimize-autoloader for a production-ready build.
RUN composer update --no-dev --optimize-autoloader

# Now, copy the rest of the application source code
COPY . .

# Stage 2: Final production image
# We use the most recent stable tag for the richarvey image.
# NOTE: This image is based on PHP 8.2. If your application code is not
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