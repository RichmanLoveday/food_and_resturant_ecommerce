FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

# App env config
ENV SKIP_COMPOSER 1
ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 1
ENV REAL_IP_HEADER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr
ENV COMPOSER_ALLOW_SUPERUSER 1

# ✅ Copy the Laravel deploy script to startup.d so it's executed automatically
COPY scripts/00-laravel-deploy.sh /etc/startup.d/00-laravel-deploy.sh
RUN chmod +x /etc/startup.d/00-laravel-deploy.sh

# Base image already uses /start.sh, no need to override CMD
CMD ["/start.sh"]
