#!/bin/sh
set -e

cd /var/www/html

# Ensure writable dirs
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    storage/logs storage/app/public bootstrap/cache database
chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true
chmod -R ug+rwx storage bootstrap/cache database 2>/dev/null || true

# Create SQLite database if not exists
DB_FRESH=false
if [ ! -f database/database.sqlite ]; then
  echo "Creating SQLite database..."
  touch database/database.sqlite
  chown www-data:www-data database/database.sqlite
  chmod 664 database/database.sqlite
  DB_FRESH=true
fi

# Generate APP_KEY if missing
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
  echo "Generating APP_KEY..."
  export APP_KEY="$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")"
fi

# Write all essential env vars to .env so config:cache picks them up
# (Laravel config:cache reads .env, ignores OS env vars after caching)
cat > .env <<ENVEOF
APP_NAME=${APP_NAME:-Pilketos}
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}
ASSET_URL=${ASSET_URL:-${APP_URL:-http://localhost}}
APP_TIMEZONE=${APP_TIMEZONE:-Asia/Jakarta}
LOG_CHANNEL=${LOG_CHANNEL:-stderr}
DB_CONNECTION=${DB_CONNECTION:-sqlite}
DB_DATABASE=${DB_DATABASE:-/var/www/html/database/database.sqlite}
SESSION_DRIVER=${SESSION_DRIVER:-file}
CACHE_STORE=${CACHE_STORE:-file}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
ENVEOF
echo ".env written from Docker environment."

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Remove cached package discoveries from dev
rm -f bootstrap/cache/packages.php bootstrap/cache/routes.php bootstrap/cache/config.php bootstrap/cache/events.php bootstrap/cache/services.php

# Migrate
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  echo "Running migrations..."
  php artisan migrate --force
fi

# Seed (first boot only — when DB was freshly created)
if [ "${RUN_SEEDERS:-true}" = "true" ] && [ "$DB_FRESH" = "true" ]; then
  echo "Running seeders (fresh database)..."
  php artisan db:seed --force || true
else
  echo "Skipping seeders (database already exists)."
fi

# Storage symlink
if [ -L public/storage ]; then
  :
elif [ -d public/storage ] || [ -e public/storage ]; then
  echo "Replacing public/storage with symlink..."
  rm -rf public/storage
fi
php artisan storage:link 2>/dev/null || ln -sfn /var/www/html/storage/app/public /var/www/html/public/storage || true
chown -h www-data:www-data public/storage 2>/dev/null || true

# Copy seed assets (foto_calon) into storage if not present
if [ -d /var/www/html/docker/seed-assets/foto_calon ]; then
  mkdir -p storage/app/public/foto_calon
  for f in /var/www/html/docker/seed-assets/foto_calon/*; do
    fname=$(basename "$f")
    if [ ! -f "storage/app/public/foto_calon/$fname" ]; then
      echo "Seeding asset: foto_calon/$fname"
      cp "$f" "storage/app/public/foto_calon/$fname"
    fi
  done
  chown -R www-data:www-data storage/app/public/foto_calon 2>/dev/null || true
fi

# Cache config & routes
php artisan config:cache || true
php artisan route:cache 2>/dev/null || php artisan route:clear || true
php artisan view:cache || true

exec "$@"
