#!/bin/sh
set -e

: "${FORGE_COMPOSER:=composer}"
: "${FORGE_PHP:=php}"
: "${FORGE_PHP_FPM:=php-fpm}"

# Install PHP dependencies matching the container runtime.
$FORGE_COMPOSER install --no-interaction --no-scripts --prefer-dist

# Ensure the application environment file exists and is configured.
if [ ! -f .env ] && [ -f .env.example ]; then
  cp .env.example .env
fi

set_env() {
  key="$1"
  value="$2"

  if [ -z "$key" ] || [ -z "$value" ]; then
    return
  fi

  if grep -q "^${key}=" .env 2>/dev/null; then
    sed -i "s#^${key}=.*#${key}=${value}#" .env
  else
    printf '%s=%s\n' "$key" "$value" >> .env
  fi
}

set_env "DB_CONNECTION" "${DB_CONNECTION:-mysql}"
set_env "DB_HOST" "${DB_HOST:-127.0.0.1}"
set_env "DB_PORT" "${DB_PORT:-3306}"
set_env "DB_DATABASE" "${DB_DATABASE:-homestead}"
set_env "DB_USERNAME" "${DB_USERNAME:-homestead}"
set_env "DB_PASSWORD" "${DB_PASSWORD:-secret}"

# Generate the application key.
if [ -f artisan ]; then
  $FORGE_PHP artisan key:generate --force
fi

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
  $FORGE_PHP artisan migrate --force --seed
fi

# Start the default process from the base image once initialization is complete.
if [ -x /opt/docker/bin/entrypoint ]; then
  exec /opt/docker/bin/entrypoint supervisord
fi

exec supervisord
