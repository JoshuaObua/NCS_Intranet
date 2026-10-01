#!/bin/bash
# Load the application schema (pg_dump of the NCS database) on first start.
set -euo pipefail
psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" -q -f /schema/database_schema.sql
echo "NCS schema loaded."
