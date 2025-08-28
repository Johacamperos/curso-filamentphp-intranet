FROM webdevops/php-nginx:8.3


ENV WEB_DOCUMENT_ROOT=/app/public

# Paquetes del sistema necesarios
RUN apt-get update && apt-get install -y \
    git unzip sqlite3 libsqlite3-dev \
    libicu-dev \
    libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    zlib1g-dev libzip-dev \
 && rm -rf /var/lib/apt/lists/*

# Extensiones PHP (GD con jpeg/webp/freetype, SQLite, intl, zip, etc.)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" \
    pdo_sqlite bcmath intl gd zip pcntl opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Cache de dependencias (aprovecha capa)
COPY composer.json composer.lock* ./
RUN composer install --no-interaction --prefer-dist --no-scripts --no-progress || true

# --- Elegir .env por build-arg (dev/prod) ---
ARG APP_ENV=dev
COPY .env.${APP_ENV} .env

# Código de la app
COPY . .

# Crear directorios/archivos necesarios y permisos (el entrypoint vuelve a ajustarlos en cada arranque)
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views database \
 && touch storage/logs/laravel.log database/database.sqlite \
 && chown -R application:application storage bootstrap/cache database \
 && chmod -R ug+rwX storage bootstrap/cache database \
 && chmod 664 storage/logs/laravel.log database/database.sqlite

# Hook de arranque: corre artisan/migraciones/seed/permisos automáticamente
COPY docker/entrypoint/99-laravel.sh /opt/docker/provision/entrypoint.d/99-laravel.sh
# Normaliza fin de línea por si el archivo viene de Windows
RUN sed -i 's/\r$//' /opt/docker/provision/entrypoint.d/99-laravel.sh \
 && chmod +x /opt/docker/provision/entrypoint.d/99-laravel.sh

EXPOSE 80
