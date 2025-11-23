FROM php:8.2-fpm

# -----------------------------
# Extensiones necesarias
# -----------------------------
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# -----------------------------
# Instalar Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# Configurar carpeta del proyecto
# -----------------------------
WORKDIR /var/www

# Copiar archivos del proyecto a la imagen
COPY . .

# -----------------------------
# Instalar dependencias Laravel
# -----------------------------
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Generar key (ignorar error si ya existe)
RUN php artisan key:generate || true

# -----------------------------
# Asignar permisos correctos
# -----------------------------
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

CMD ["php-fpm"]
