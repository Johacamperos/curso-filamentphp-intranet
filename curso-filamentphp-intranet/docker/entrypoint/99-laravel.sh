#!/usr/bin/env sh
set -e

cd /app || exit 0
[ -f .env ] || exit 0

echo "[entrypoint] Preparando Laravel…"

# --- FIX PERMISOS (idempotente) ---
mkdir -p storage/logs storage/framework/{cache,sessions,views} database
: > storage/logs/laravel.log    # crea o vacía el log
[ -f database/database.sqlite ] || : > database/database.sqlite

chown -R application:application storage bootstrap/cache database || true
chmod -R ug+rwX storage bootstrap/cache database || true
chmod 664 storage/logs/laravel.log database/database.sqlite || true
# ----------------------------------

# APP key + storage
php artisan key:generate --force >/dev/null 2>&1 || true
php artisan storage:link >/dev/null 2>&1 || true

# Instala Filament si falta
if [ ! -f config/filament.php ]; then
  echo "[entrypoint] Instalando Filament Panels…"
  php artisan filament:install --panels --no-interaction >/dev/null 2>&1 || true
fi

# Modo según .env (lee APP_ENV desde archivo)
ENV_MODE="$(grep -E '^APP_ENV=' .env | cut -d= -f2- | tr -d '\r' | tr -d '"')"
if [ "$ENV_MODE" = "production" ]; then
  php artisan config:cache >/dev/null 2>&1 || true
  php artisan route:cache  >/dev/null 2>&1 || true
  php artisan view:cache   >/dev/null 2>&1 || true
else
  php artisan optimize:clear >/dev/null 2>&1 || true
  php artisan cache:clear    >/dev/null 2>&1 || true
fi

# Migraciones + seed
php artisan migrate --force --no-interaction >/dev/null 2>&1 || true
echo "[entrypoint] Ejecutando FilamentAdminSeeder…"
php artisan db:seed --class="Database\\Seeders\\FilamentAdminSeeder" --force --no-interaction >/dev/null 2>&1 || true

echo "[entrypoint] Laravel OK"
