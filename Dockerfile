FROM php:8.2-cli

# Минимальные зависимости
RUN apt-get update && apt-get install -y zip unzip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Установка зависимостей
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Простые права
RUN chmod -R 775 storage

EXPOSE 8000

# Простая команда
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]