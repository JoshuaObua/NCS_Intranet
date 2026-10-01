#!/bin/bash
# Create the unprivileged role the application connects as. It must exist
# before the schema loads, because database_schema.sql grants to it by name.
set -euo pipefail
: "${APP_DB_USER:?APP_DB_USER must be set}"
: "${APP_DB_PASSWORD:?APP_DB_PASSWORD must be set}"

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -q \
  -v app_user="$APP_DB_USER" -v app_password="$APP_DB_PASSWORD" -v db="$POSTGRES_DB" <<'SQL'
CREATE ROLE :"app_user" LOGIN PASSWORD :'app_password';
GRANT CONNECT, TEMPORARY ON DATABASE :"db" TO :"app_user";
SQL
echo "Application role ${APP_DB_USER} created."
