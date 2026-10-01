#!/bin/bash
# Create the initial administrator from ADMIN_* environment variables.
# The password is bcrypt-hashed in the database (pgcrypto), which PHP's
# password_verify() accepts, so it never needs to be stored in plain text.
set -euo pipefail
: "${ADMIN_EMAIL:?ADMIN_EMAIL must be set}"
: "${ADMIN_PASSWORD:?ADMIN_PASSWORD must be set}"

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -q \
  -v email="$ADMIN_EMAIL" \
  -v password="$ADMIN_PASSWORD" \
  -v first_name="${ADMIN_FIRST_NAME:-System}" \
  -v last_name="${ADMIN_LAST_NAME:-Administrator}" <<'SQL'
CREATE EXTENSION IF NOT EXISTS pgcrypto;
INSERT INTO public.ncs_users
  (first_name, last_name, user_type, is_admin, role_id, email, password, status,
   job_title, language, created_at, deleted)
VALUES
  (:'first_name', :'last_name', 'staff', 1, 0, :'email', crypt(:'password', gen_salt('bf', 10)), 'active',
   'Administrator', '', now(), 0);
UPDATE public.ncs_settings SET setting_value = :'email' WHERE setting_name = 'email_sent_from_address';
DROP EXTENSION pgcrypto;
SQL
echo "Administrator ${ADMIN_EMAIL} created."
