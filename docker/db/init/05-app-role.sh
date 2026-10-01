#!/bin/bash
# Create the unprivileged role the application connects as, and hand it
# ownership of every object in the public schema (the app alters its own
# tables during updates and plugin installs).
set -euo pipefail
: "${APP_DB_USER:?APP_DB_USER must be set}"
: "${APP_DB_PASSWORD:?APP_DB_PASSWORD must be set}"

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -q \
  -v app_user="$APP_DB_USER" -v app_password="$APP_DB_PASSWORD" -v db="$POSTGRES_DB" <<'SQL'
CREATE ROLE :"app_user" LOGIN PASSWORD :'app_password';
GRANT CONNECT, TEMPORARY ON DATABASE :"db" TO :"app_user";
ALTER SCHEMA public OWNER TO :"app_user";
SELECT set_config('ncs.app_user', :'app_user', false);
DO $$
DECLARE
  r record;
  app_user text := current_setting('ncs.app_user');
BEGIN
  FOR r IN SELECT c.relname, c.relkind FROM pg_class c JOIN pg_namespace n ON n.oid = c.relnamespace
           WHERE n.nspname = 'public' AND c.relkind IN ('r', 'v', 'm', 'S', 'p', 'f')
  LOOP
    IF r.relkind = 'S' AND EXISTS (SELECT 1 FROM pg_depend d JOIN pg_class s ON s.oid = d.objid
                                   WHERE s.relname = r.relname AND d.deptype IN ('a', 'i')) THEN
      CONTINUE; -- owned sequences follow their table
    END IF;
    EXECUTE format('ALTER %s public.%I OWNER TO %I',
      CASE r.relkind WHEN 'v' THEN 'VIEW' WHEN 'm' THEN 'MATERIALIZED VIEW' WHEN 'S' THEN 'SEQUENCE'
                     WHEN 'f' THEN 'FOREIGN TABLE' ELSE 'TABLE' END,
      r.relname, app_user);
  END LOOP;
  FOR r IN SELECT p.oid::regprocedure AS sig FROM pg_proc p JOIN pg_namespace n ON n.oid = p.pronamespace
           WHERE n.nspname = 'public'
  LOOP
    EXECUTE format('ALTER ROUTINE %s OWNER TO %I', r.sig, app_user);
  END LOOP;
  FOR r IN SELECT t.typname FROM pg_type t JOIN pg_namespace n ON n.oid = t.typnamespace
           WHERE n.nspname = 'public' AND t.typtype IN ('e', 'd', 'c')
             AND NOT EXISTS (SELECT 1 FROM pg_class c WHERE c.reltype = t.oid)
  LOOP
    EXECUTE format('ALTER TYPE public.%I OWNER TO %I', r.typname, app_user);
  END LOOP;
END $$;
SQL
echo "Application role ${APP_DB_USER} created and given ownership of the schema."
