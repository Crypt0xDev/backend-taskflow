#!/bin/sh
set -e

cd /var/www/html

# Estructura de storage (por si hay volúmenes montados vacíos)
mkdir -p storage/framework/cache/data \
         storage/framework/sessions \
         storage/framework/views \
         storage/app/public \
         storage/logs \
         bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# APP_KEY: si no viene por variables de entorno, se genera una efímera
if [ -z "${APP_KEY}" ]; then
    echo "[entrypoint] AVISO: APP_KEY no definida. Generando una temporal."
    echo "[entrypoint] Define APP_KEY en Dokploy o se invalidarán las sesiones en cada deploy."
    export APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
fi

# Enlace simbólico de storage público
php artisan storage:link --force >/dev/null 2>&1 || true

# Esperar a la base de datos y migrar (opcional, activado con RUN_MIGRATIONS=true)
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "[entrypoint] Esperando a la base de datos..."
    i=0
    until php -r "
        \$h=getenv('DB_HOST') ?: '127.0.0.1';
        \$p=getenv('DB_PORT') ?: '5432';
        \$c=@fsockopen(\$h, (int)\$p, \$e, \$s, 2);
        exit(\$c ? 0 : 1);
    "; do
        i=$((i+1))
        [ "$i" -ge 30 ] && echo "[entrypoint] La base de datos no respondió. Continuando igualmente." && break
        sleep 2
    done

    echo "[entrypoint] Ejecutando migraciones..."
    php artisan migrate --force || echo "[entrypoint] Las migraciones fallaron."
fi

# Cachés de producción (sin view:cache: es una API pura, sin resources/views)
php artisan config:clear >/dev/null 2>&1 || true
php artisan config:cache
php artisan route:cache

exec "$@"
