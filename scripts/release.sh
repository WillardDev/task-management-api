#!/usr/bin/env bash
set -euo pipefail

DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
MAX_RETRIES=${MAX_RETRIES:-30}
SLEEP_SECONDS=${SLEEP_SECONDS:-2}

echo "Waiting for database at ${DB_HOST}:${DB_PORT} (max ${MAX_RETRIES} attempts)"

retries=0
while ! bash -c "</dev/tcp/${DB_HOST}/${DB_PORT}" >/dev/null 2>&1; do
  retries=$((retries+1))
  if [ "$retries" -ge "$MAX_RETRIES" ]; then
    echo "Timed out waiting for database at ${DB_HOST}:${DB_PORT} after ${MAX_RETRIES} attempts"
    exit 1
  fi
  echo "[$(date +%T)] Database not ready, retry ${retries}/${MAX_RETRIES}..."
  sleep "$SLEEP_SECONDS"
done

echo "Database is reachable — running migrations"
php artisan migrate --force

echo "Migrations finished"
