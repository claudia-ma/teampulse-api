FROM php:8.2-apache

# System deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App
WORKDIR /var/www/html
COPY . .

# Laravel (si tu proyecto está dentro de carpeta "Laravel", ajusta aquí abajo)
# Si tu repo tiene Laravel en la raíz, deja el siguiente bloque comentado.
# Si tu repo tiene /Laravel (carpeta), descomenta estas 2 líneas:
# WORKDIR /var/www/html/Laravel

RUN composer install --no-dev --optimize-autoloader

# Apache document root -> /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Render usa PORT
EXPOSE 10000
CMD ["bash", "-lc", "apache2-foreground"]