FROM php:8.4-fpm

# Cài đặt các extension hệ thống cần thiết cho Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd

# Lấy Composer chính thức vào trong container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Cài đặt dependency bên trong container để đồng bộ
RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

EXPOSE 9000
CMD ["php-fpm"]