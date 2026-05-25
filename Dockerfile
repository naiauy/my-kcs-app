# Stage 1: Build dependency ด้วย Composer
FROM php:8.4-apache AS builder

# ติดตั้ง System dependencies และ PHP extensions ที่จำเป็นสำหรับ E-commerce
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libjpeg-dev libfreetype6-dev libzip-dev zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip bcmath

# คัดลอก Composer มาใช้งาน
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Stage 2: Production Runtime
FROM php:8.4-apache

# ติดตั้ง Runtime dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev \
    && docker-php-ext-install pdo_mysql gd zip bcmath \
    && pecl install redis && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# เปิดใช้งาน mod_rewrite ของ Apache (สำคัญมากสำหรับ Routing ของ Laravel)
RUN a2enmod rewrite

# เปลี่ยน Document Root ของ Apache ให้ชี้ไปที่โฟลเดอร์ public ของ Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY --from=builder /var/www/html /var/www/html

# ตั้งค่าสิทธิ์ให้ Apache (www-data) สามารถเขียนไฟล์ใน storage ได้
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# ทํา Optimization Cache สำหรับ Production
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

EXPOSE 80
CMD ["apache2-foreground"]