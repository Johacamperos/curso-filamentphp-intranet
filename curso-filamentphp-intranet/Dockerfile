# Imagen con extensiones comunes ya precompiladas
FROM webdevops/php-nginx-dev:8.3-alpine

ENV WEB_DOCUMENT_ROOT=/app/public

# Necesitamos root solo para instalar utilidades del sistema (no PHP)
USER root
RUN apk add --no-cache git unzip sqlite  # sqlite CLI opcional para pruebas

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress || true

ARG APP_ENV=dev
COPY .env.${APP_ENV} .env
COPY . .

# Permisos para Laravel
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} database \
 && touch storage/logs/laravel.log database/database.sqlite \
 && chown -R application:application storage bootstrap/cache database \
 && chmod -R ug+rwX storage bootstrap/cache database \
 && chmod 664 storage/logs/laravel.log database/database.sqlite

# Hook de arranque (si lo usas)
COPY docker/entrypoint/99-laravel.sh /opt/docker/provision/entrypoint.d/99-laravel.sh
RUN sed -i 's/\r$//' /opt/docker/provision/entrypoint.d/99-laravel.sh \
 && chmod +x /opt/docker/provision/entrypoint.d/99-laravel.sh

# Volver al usuario no-root
USER application

EXPOSE 8080
