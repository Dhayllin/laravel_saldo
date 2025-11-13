#!/bin/sh
set -e

: "${FORGE_COMPOSER:=composer}"
: "${FORGE_PHP:=php}"
: "${FORGE_PHP_FPM:=php-fpm}"

# Install PHP dependencies optimized for production.
$FORGE_COMPOSER install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Prevent concurrent php-fpm reloads.
touch /tmp/fpmlock 2>/dev/null || true
if command -v flock >/dev/null 2>&1; then
  (
    flock -w 10 9 || exit 1
    echo 'Reloading PHP FPM...'
    if command -v service >/dev/null 2>&1; then
      service "$FORGE_PHP_FPM" reload || true
    fi
  ) 9</tmp/fpmlock
fi

# Run database migrations if the artisan binary is present.
if [ -f artisan ]; then
  $FORGE_PHP artisan migrate --force
fi

# Start the default process from the base image once initialization is complete.
if [ -x /opt/docker/bin/entrypoint ]; then
  exec /opt/docker/bin/entrypoint supervisord
fi

exec supervisord
