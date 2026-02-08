FROM php:8.2-cli

# Устанавливаем только необходимые зависимости для Laravel
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install \
    zip \
    mbstring \
    pdo

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/html

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Создаем необходимые директории
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p bootstrap/cache

# Настраиваем права доступа
RUN chmod -R 775 storage bootstrap/cache

# Экспорт порта
EXPOSE 8000

# Команда запуска
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]