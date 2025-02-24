# Etapa 1: Construcción de dependencias con Composer
FROM php:8.2-fpm

# Instalamos las dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    mariadb-client \
    && docker-php-ext-install pdo pdo_mysql

# Instalamos Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Verificar la instalación de Composer
RUN composer --version

# Crear directorio de trabajo
WORKDIR /var/www

# Copiar archivos de Laravel
COPY . .

# Establecemos la variable de entorno para permitir superusuario en Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Establecemos permisos
USER root
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Exponemos el puerto 9000 (PHP-FPM)
EXPOSE 9000

# Comando para iniciar PHP-FPM
CMD ["php-fpm"]
