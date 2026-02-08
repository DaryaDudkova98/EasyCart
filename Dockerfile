# Используем официальный PHP образ
FROM php:8.2-cli

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install \
    zip \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    pdo_mysql \
    pdo_sqlite \
    sockets

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настраиваем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы проекта
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Настраиваем права доступа
RUN chmod -R 775 storage bootstrap/cache

# Порт
EXPOSE 8000

# Запускаем встроенный PHP сервер
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]