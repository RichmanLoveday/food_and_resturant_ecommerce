FROM richarvey/nginx-php-fpm:3.1.6

# Copy all project files
COPY . .

# Laravel environment variables
ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# ✅ Fix permissions before Laravel boots (so early logging doesn't fail)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ✅ Copy your Laravel deployment script
COPY scripts/start.sh /etc/startup.d/00-laravel-deploy.sh
RUN chmod +x /etc/startup.d/00-laravel-deploy.sh

RUN chmod -R ugo+rw storage

# ✅ Let the base image handle startup via its entrypoint
CMD ["/start.sh"]
