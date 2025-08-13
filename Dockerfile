FROM php:8.3-apache

# 📌 Instalación de extensiones necesarias (ya existentes + MongoDB)
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git libssl-dev pkg-config \
    && docker-php-ext-install pdo pdo_mysql \
    # --- Instalación extensión oficial MongoDB ---
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# 📌 Habilitar mod_rewrite para Symfony
RUN a2enmod rewrite

# 📌 Copio los archivos del proyecto
COPY . /var/www/html

# 📌 Cambio DocumentRoot para Symfony (/public)
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 📌 Permisos para Apache
RUN chown -R www-data:www-data /var/www/html

# 📌 Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
