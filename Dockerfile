# Usamos una imagen base de PHP con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias para Laravel y PostgreSQL
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev libpq-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuramos Apache para que Laravel funcione bien
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Definimos la carpeta de trabajo
WORKDIR /var/www/html

# Copiamos todos los archivos de tu proyecto
COPY . .

# Instalamos las dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Damos permisos a las carpetas que Laravel necesita escribir
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponemos el puerto 80
EXPOSE 80