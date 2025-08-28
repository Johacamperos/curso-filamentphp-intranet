# --- Base ---
FROM webdevops/php-nginx:8.3-alpine

# Document root para nginx/php
ENV WEB_DOCUMENT_ROOT=/app/public

# Usar root para instalar paquetes y compilar extensions
USER root

# Paquetes de sistema y toolchain para compilar extensiones PHP
RUN apk add --no-cache \
    git unzip sqlite sqlite-dev \
    icu-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    zlib-dev libzip-dev \
    $PHPIZE_DEPS \
    tar

# Evita problemas de overlayfs al extraer el código fuente de PHP
ENV TAR_OPTIONS="--no-same-owner --no-same-permissions"
RUN mkdir -p /usr/src/php && chmod -R 0777 /usr/src

# Extensiones PHP (GD con freetype/jpeg/webp, intl, zip, etc.)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" \
    pdo_sqlite bcmath intl gd zip pcntl opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --- App ---
WORKDIR /app

# Instala dependencias de Composer con cache de capas
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress || true

# Selección de .env según build arg (dev por defecto)
ARG APP_ENV=dev
COPY .env.${APP_ENV} .env

# Código fuente
COPY . .

# Directorios/archivos requeridos y permisos
RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} database \
 && touch storage/logs/laravel.log database/database.sqlite \
 && chown -R application:application storage bootstrap/cache database \
 && chmod -R ug+rwX storage bootstrap/cache database \
 && chmod 664 storage/logs/laravel.log database/database.sqlite

# Hook de arranque (migraciones/seed/permisos, etc.)
COPY docker/entrypoint/99-laravel.sh /opt/docker/provision/entrypoint.d/99-laravel.sh
RUN sed -i 's/\r$//' /opt/docker/provision/entrypoint.d/99-laravel.sh \
 && chmod +x /opt/docker/provision/entrypoint.d/99-laravel.sh

# Volver al usuario por defecto de la imagen
USER application

EXPOSE 80
