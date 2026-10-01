#!/bin/sh
# Render CodeIgniter's .env from the container environment, make sure the
# persistent volumes are writable by Apache, then start it.
set -e
cd /var/www/html

{
  echo "CI_ENVIRONMENT = ${CI_ENVIRONMENT:-production}"
  echo "app.baseURL = \"${APP_BASE_URL}\""
  echo "app.encryption_key = \"${APP_ENCRYPTION_KEY}\""
  echo "database.hostname = ${DB_HOST:-db}"
  echo "database.port = ${DB_PORT:-5432}"
  echo "database.database = ${DB_NAME}"
  echo "database.username = ${DB_USER}"
  echo "database.password = \"${DB_PASSWORD}\""
  echo "database.driver = Postgre"
  echo "database.prefix = ncs_"
  env | grep '^UGPASS_' | sed 's/^\([^=]*\)=\(.*\)$/\1 = "\2"/' || true
} > .env
chown root:www-data .env
chmod 640 .env

for d in writable/cache writable/logs writable/session writable/uploads writable/debugbar files; do
  mkdir -p "$d"
done
chown -R www-data:www-data writable files

exec docker-php-entrypoint "$@"
