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
docker compose build --pull app
docker compose up -d --remove-orphans
docker image prune -f >/dev/null
docker compose ps
