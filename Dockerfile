FROM php:8.2-cli

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libonig-dev \
    libzip-dev

# Включаем расширения
RUN docker-php-ext-install \
    mbstring \
    zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]