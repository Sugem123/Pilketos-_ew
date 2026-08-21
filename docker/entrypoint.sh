#!/bin/sh
set -e

cd /var/www/html

# Ensure writable dirs
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    storage/logs storage/app/public bootstrap/cache database
chown -R www-data:www-data storage bootstrap/cache database 2>/dev/null || true
chmod -R ug+rwx storage bootstrap/cache database 2>/dev/null || true

# Create SQLite database if not exists
if [ ! -f database/database.sqlite ]; then
  echo "Creating SQLite database..."
  touch database/database.sqlite
  chown www-data:www-data database/database.sqlite
  chmod 664 database/database.sqlite
fi

# Generate APP_KEY if missing
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
  echo "Generating APP_KEY..."
  export APP_KEY="$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")"
  echo "APP_KEY=${APP_KEY}" >> .env 2>/dev/null || true
  echo "APP_KEY generated (set it permanently in Coolify env for restarts)."
fi

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

# Seed (first boot only)
if [ "${RUN_SEEDERS:-true}" = "true" ]; then
  echo "Running seeders..."
  php artisan db:seed --force || true
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

# Cache config & routes
php artisan config:cache || true
php artisan route:cache 2>/dev/null || php artisan route:clear || true
php artisan view:cache || true

exec "$@"
