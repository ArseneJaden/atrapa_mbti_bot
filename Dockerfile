# Base image PHP 8.3 CLI + Composer
FROM php:8.4-cli

# Instalar extensiones necesarias de Laravel y PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar el proyecto al contenedor
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto que Render usará
EXPOSE 10000

# Comando para arrancar Laravel
CMD php artisan serve --host=0.0.0.0 --port=$PORT

RUN php artisan migrate --force