FROM php:8.2-cli

RUN apt-get update && apt-get install -y zip unzip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && chmod -R 777 storage bootstrap/cache \
    && touch storage/logs/laravel.log \
    && chmod 666 storage/logs/laravel.log

# Создаем правильный .env
RUN echo "APP_ENV=local" > .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_URL=http://localhost" >> .env && \
    echo "LOG_CHANNEL=stack" >> .env && \
    echo "LOG_DEPRECATIONS_CHANNEL=null" >> .env && \
    echo "LOG_LEVEL=debug" >> .env && \
    echo "DB_CONNECTION=array" >> .env && \
    echo "SESSION_DRIVER=array" >> .env && \
    echo "CACHE_DRIVER=file" >> .env

EXPOSE 8000

# Запускаем с выводом ошибок
CMD ["sh", "-c", "php artisan key:generate --force && php artisan config:clear && php artisan cache:clear && php -d display_errors=1 -d error_reporting=E_ALL artisan serve --host=0.0.0.0 --port=8000 2>&1"]