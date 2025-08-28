FROM webdevops/php-nginx:8.3-alpine

ENV WEB_DOCUMENT_ROOT=/app/public

# Paquetes y extensiones
RUN apk add --no-cache \
    git unzip pkgconfig \
    icu-dev sqlite sqlite-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    zlib-dev libzip-dev \
    $PHPIZE_DEPS \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j$(nproc) pdo_sqlite bcmath intl gd zip pcntl opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app


# cache composer
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress || true

# --- elegir env por build-arg ---
ARG APP_ENV=dev
COPY .env.${APP_ENV} .env

# Código de la app
COPY . .

# Asegurar SQLite y permisos (no molesta si usas MySQL en prod)
RUN mkdir -p database && [ -f database/database.sqlite ] || touch database/database.sqlite \
 && chown -R application:application storage bootstrap/cache database \
 && chmod -R ug+rwx storage bootstrap/cache database

# Hook de arranque: corre artisan y migraciones automáticamente al iniciar
COPY docker/entrypoint/99-laravel.sh /opt/docker/provision/entrypoint.d/99-laravel.sh
RUN chmod +x /opt/docker/provision/entrypoint.d/99-laravel.sh

EXPOSE 80
