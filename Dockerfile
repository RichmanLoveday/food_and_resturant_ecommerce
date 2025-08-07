# Multi-stage build for a smaller, more secure final image

# Stage 1: Build the application with Composer
# Use the official PHP 8.3 CLI image as the base.
FROM php:8.3-cli AS composer_builder

# Install Composer on top of the PHP image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Install the necessary PHP extensions
# The `openspout` package requires the `zip` extension.
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

# Set the working directory inside the container
WORKDIR /app

# Copy only composer.json to leverage Docker's caching
COPY composer.json ./

# Run composer update to generate a new composer.lock file from scratch.
# We're keeping the `-v` flag here for one last check.
RUN composer update --no-dev --optimize-autoloader -v > composer-output.log 2>&1 || (cat composer-output.log && exit 2)

# Now, copy the rest of the application source code
COPY . .

# Stage 2: Final production image
# The richarvey image is based on PHP 8.2.
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
RUN mkdir -p storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy the start script
COPY scripts/start.sh /etc/startup.d/00-laravel-deploy.sh
RUN chmod +x /etc/startup.d/00-laravel-deploy.sh

# Let the base image handle startup via its entrypoint
CMD ["/start.sh"]