#!/usr/bin/env bash
# Pull the latest code from GitHub and rebuild/restart the containers.
# Usage (on the server, from the repo directory):  ./deploy.sh
set -euo pipefail
cd "$(dirname "$0")"

if [ ! -f .env ]; then
  echo "Missing .env - copy .env.docker.example to .env and fill it in first." >&2
  exit 1
fi

git pull --ff-only
docker network inspect ncs_gateway_net >/dev/null 2>&1 || docker network create ncs_gateway_net
docker compose build --pull app
docker compose up -d --remove-orphans
# Shared HTTPS edge (also serves the other NCS sites); reload picks up Caddyfile changes
docker compose -f docker/edge/docker-compose.yml up -d
docker compose -f docker/edge/docker-compose.yml exec -T caddy caddy reload --config /etc/caddy/Caddyfile >/dev/null
docker image prune -f >/dev/null
docker compose ps
