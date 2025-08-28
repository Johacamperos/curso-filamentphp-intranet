FROM webdevops/php-nginx:8.3-alpine

ENV WEB_DOCUMENT_ROOT=/app/public

# Paquetes y headers para compilar extensiones
RUN apk add --no-cache \
    git unzip sqlite sqlite-dev \
    icu-dev \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    zlib-dev libzip-dev autoconf g++ make

# Extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" \
    pdo_sqlite bcmath intl gd zip pcntl opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress || true

ARG APP_ENV=dev
COPY .env.${APP_ENV} .env
COPY . .

RUN mkdir -p storage/logs storage/framework/{cache,sessions,views} database \
 && touch storage/logs/laravel.log database/database.sqlite \
 && chown -R application:application storage bootstrap/cache database \
 && chmod -R ug+rwX storage bootstrap/cache database \
 && chmod 664 storage/logs/laravel.log database/database.sqlite

COPY docker/entrypoint/99-laravel.sh /opt/docker/provision/entrypoint.d/99-laravel.sh
RUN sed -i 's/\r$//' /opt/docker/provision/entrypoint.d/99-laravel.sh \
 && chmod +x /opt/docker/provision/entrypoint.d/99-laravel.sh

EXPOSE 80
