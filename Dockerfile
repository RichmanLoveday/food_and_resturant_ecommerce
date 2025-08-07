# Multi-stage build for a smaller, more secure final image

# Stage 1: Build the application with Composer
# We use a standard Composer image with PHP 8.3 to ensure platform requirements are met.
FROM composer:2.7 AS composer_builder

# Set the working directory inside the container
WORKDIR /app

# Copy only composer files to leverage Docker's caching
COPY composer.json composer.lock ./

# Install Composer dependencies, skipping dev dependencies
# Use --no-dev to keep the production image small
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the application source code
COPY . .

# Stage 2: Final production image
# We use a base image with a compatible PHP version.
# The richarvey/nginx-php-fpm:3.1.6 tag is associated with a specific PHP version.
# If you need PHP 8.3, you might need to use a different image or a more specific tag.
# A safer alternative is to use a specific, known-to-exist tag.
# Let's revert to a more stable, widely available base image that is known to work.
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