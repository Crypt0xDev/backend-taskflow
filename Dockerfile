# ---------- Etapa 1: dependencias PHP ----------
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

# ---------- Etapa 2: imagen final ----------
# API puro (sin Vite/Tailwind): el frontend es un proyecto Next.js aparte,
# así que aquí no se construyen assets.
FROM php:8.3-fpm-alpine

# nginx + supervisor + extensiones PHP que necesita Laravel con PostgreSQL
RUN apk add --no-cache \
        nginx \
        supervisor \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        pgsql \
        bcmath \
        intl \
        zip \
        gd \
        opcache \
        exif \
        pcntl \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

# Código de la aplicación
COPY . .

# Dependencias PHP construidas en la etapa anterior
COPY --from=vendor /app/vendor ./vendor

# Configuración de nginx / php / supervisor / entrypoint
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/99-app.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# Autoload optimizado, ya con el código de la app presente
COPY --from=vendor /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --no-dev --optimize --no-interaction \
    && php artisan package:discover --ansi \
    && rm -f /usr/bin/composer

# Permisos (incluye los temporales de nginx, que corre como www-data)
RUN mkdir -p storage/framework/cache/data storage/framework/sessions \
             storage/framework/views storage/app/public storage/logs \
             bootstrap/cache /var/lib/nginx/tmp /run/nginx \
    && chown -R www-data:www-data storage bootstrap/cache /var/lib/nginx /run/nginx \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["entrypoint"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
