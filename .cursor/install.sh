#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

npm ci
npm run build

composer install --no-interaction --prefer-dist

cd storybook
composer install --no-interaction --prefer-dist

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --ansi
fi

composer assets --ansi
