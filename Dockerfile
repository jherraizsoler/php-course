# =============================================================================
#  PHP Course — imagen PHP 8.3 + Apache con las extensiones que usa el curso.
# =============================================================================
FROM php:8.3-apache

# Extensiones nativas: pdo_mysql, gd, zip, intl, mbstring (fileinfo viene activa)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev libonig-dev \
        unzip git \
    && docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli gd zip intl mbstring \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Ajustes de PHP (temp/sesiones/zona horaria)
COPY docker/php-course.ini "$PHP_INI_DIR/conf.d/zz-course.ini"

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia el código e instala dependencias de los sub-proyectos (imagen autocontenida)
COPY . /var/www/html
RUN set -eux; \
    for d in proyectos/crud-codeigniter3/app proyectos/login-seguro; do \
        if [ -f "/var/www/html/$d/composer.json" ]; then \
            composer install -d "/var/www/html/$d" --no-interaction --no-dev --no-progress; \
        fi; \
    done; \
    chown -R www-data:www-data /var/www/html

EXPOSE 80
