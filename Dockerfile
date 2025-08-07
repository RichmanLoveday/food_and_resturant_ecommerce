# Use PHP 8.2 with FPM and Alpine for smaller image size
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    bash \
    nginx \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    shadow \
    icu-dev \
    g++ \
    make \
    autoconf

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd intl xml

# Set memory limit (fix for Render build issue)
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/99-custom.ini

# Install Composer globally
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Add nginx configuration
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy Laravel project files
COPY . .

# Ensure correct permissions
RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www \
 && chown -R www:www /var/www/html \
 && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

USER www

# Expose ports
EXPOSE 80

# Entrypoint script to run both PHP-FPM and NGINX
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]
