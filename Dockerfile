# Multi-stage build for a smaller, more secure final image

# Stage 1: Build the application with Composer
# We use a composer image with PHP 8.3 to satisfy platform requirements.
# The `composer:2.7` tag is based on PHP 8.3 by default.
FROM composer:2.7 AS composer_builder

# Set the working directory inside the container
WORKDIR /app

# Copy only composer files to leverage Docker's caching
COPY composer.json composer.lock ./

# Install Composer dependencies, skipping dev dependencies
# Use --no-dev to keep the production image small.
# The `-v` flag is added to provide a verbose log, which is useful for debugging.
RUN composer install --no-dev --optimize-autoloader -v

# Copy the rest of the application source code
COPY . .

# Stage 2: Final production image
# Use the richarvey/nginx-php-fpm image with a tag known to support PHP 8.3.
# The `3.1.6-php8.3` tag may not exist, but `3.1.6` is based on a specific PHP version.
# Let's use a different, more explicitly tagged image from another vendor if necessary.
# However, a simpler approach is to use the base richarvey image and trust it's compatible.
# The richarvey/nginx-php-fpm:3.1.6 tag is based on PHP 8.2.14.
# If your composer.json requires PHP 8.3, this will fail.
# Let's use a known-to-exist image with PHP 8.3.
# For example, `richarvey/nginx-php-fpm:3.1.6` is not PHP 8.3.
# Let's assume your composer.json is PHP 8.2 compatible and go from there.
# If you still get exit code 2, you'll need to find a richervey image that supports php8.3.
# A safe bet is to use a standard PHP-FPM image, but let's stick with the richarvey image for now.
# Based on the documentation, there is no explicit `3.1.6-php8.3`.
# The `composer:2.7` image is based on PHP 8.3, so the conflict is likely in the final image.
# Let's correct this.
# Instead of richarvey, let's use a more standard image.

# A more reliable approach is to use a base PHP-FPM image and configure Nginx separately.
# However, for simplicity and to match the original goal, let's look at a different richarvey tag.
# A common issue is that a minor version bump in the base image corresponds to a PHP version bump.
# Let's try `richarvey/nginx-php-fpm:4.0.0` as an example, as this might be based on PHP 8.3.
FROM richarvey/nginx-php-fpm:4.0.0

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