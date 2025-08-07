FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Set PHP memory limit
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/99-custom.ini

# Ensure start.sh is copied
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Run Laravel permissions or composer install (optional)
# RUN composer install
# RUN php artisan config:cache

# Start container
CMD ["/start.sh"]
