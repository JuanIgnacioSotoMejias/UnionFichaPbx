# Etapa 1: Node.js para compilar assets (Tailwind CSS, Alpine.js, etc.)
FROM node:20-alpine AS node_builder
WORKDIR /app
# Copiar todos los archivos (necesario para que Tailwind escanee las vistas)
COPY . .
# Instalar dependencias de Node y compilar para producción
RUN npm install
RUN npm run build

# Etapa 2: Composer para dependencias de PHP
FROM composer:2.7 AS php_builder
WORKDIR /app
# Copiar archivos de dependencias
COPY database/ database/
COPY composer.json composer.lock ./
# Instalar dependencias de PHP (sin paquetes de desarrollo)
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist
# Copiar el resto del código y generar el autoload optimizado
COPY . .
RUN composer dump-autoload --optimize

# Etapa 3: Imagen final de Producción con Apache y PHP 8.2
FROM php:8.2-apache

# Instalar extensiones requeridas por Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libonig-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar el módulo mod_rewrite de Apache (requerido por las rutas de Laravel)
RUN a2enmod rewrite

# Cambiar el DocumentRoot de Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar el código fuente y las dependencias desde php_builder
COPY --from=php_builder /app .

# Copiar los assets de frontend compilados desde node_builder
COPY --from=node_builder /app/public/build ./public/build

# Configurar permisos para los directorios que Laravel necesita escribir
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 80
EXPOSE 80

# El comando por defecto de la imagen php:8.2-apache ya inicia el servidor, 
# por lo que no es necesario agregar CMD a menos que quieras correr migraciones aquí.
