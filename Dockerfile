# Dockerfile (простой вариант)
FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80