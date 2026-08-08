#!/usr/bin/env bash
#
# Boots the two processes Blast needs:
#
#   1. The Laravel host app. Storybook Server renders every story by making an
#      HTTP request to APP_URL/storybook_preview, so the app has to be reachable
#      at exactly the host and port in .env's APP_URL.
#   2. Storybook itself (blast:launch), which starts the watcher that
#      regenerates stories on save.
#
# Blast's npm dependencies are installed here rather than by blast:launch, so
# that storybook-addon-pseudo-states can be added on top of them — see below.
#
# Usage: ./start.sh          (or: composer start)
#        ./start.sh --install    to force a clean reinstall of the npm deps

set -euo pipefail

cd "$(dirname "$0")"

if [ ! -f .env ]; then
    echo "No .env — run: cp .env.example .env && php artisan key:generate" >&2
    exit 1
fi

APP_URL="$(grep -E '^APP_URL=' .env | head -n1 | cut -d= -f2- | tr -d '"'"'"'"' | tr -d '\r')"
APP_URL="${APP_URL:-http://127.0.0.1:8010}"
HOST="$(printf '%s' "$APP_URL" | sed -E 's#^https?://##; s#[:/].*$##')"
HOST="${HOST:-127.0.0.1}"
PORT="$(printf '%s' "$APP_URL" | sed -nE 's#^.*:([0-9]+).*$#\1#p')"
PORT="${PORT:-8000}"

# storybook-addon-pseudo-states is not one of Blast's dependencies, but
# .storybook/main.js loads it from Blast's node_modules — that is the only tree
# where its @storybook/* peers resolve. Blast owns that directory and clears it:
# `blast:launch` runs `npm ci` whenever node_modules/@storybook is missing, and
# `installStorybook` prunes anything extraneous whenever the installed Storybook
# differs from blast.storybook_version. So do Blast's install here, add the
# addon on top, and hand Blast a tree it will leave alone.
#
# 2.1.1 is deliberate: from 2.1.2 the addon's peers demand ^7.4.6 of
# @storybook/theming, core-events and manager-api, and Blast pins 7.1.1.
BLAST_DIR="vendor/area17/blast"
PSEUDO_STATES="storybook-addon-pseudo-states@2.1.1"

# `blast:launch --install` would force its own `npm ci` and drop the addon
# again, so handle the flag here instead of passing it on.
FORCE_INSTALL=false
LAUNCH_ARGS=()
for arg in "$@"; do
    if [ "$arg" = "--install" ]; then
        FORCE_INSTALL=true
    else
        LAUNCH_ARGS+=("$arg")
    fi
done

if [ "$FORCE_INSTALL" = true ] || [ ! -d "${BLAST_DIR}/node_modules/@storybook" ]; then
    echo "==> Installing Blast's npm dependencies"
    (cd "${BLAST_DIR}" && npm ci --omit=dev --ignore-scripts)
fi

if [ ! -d "${BLAST_DIR}/node_modules/storybook-addon-pseudo-states" ]; then
    echo "==> Adding ${PSEUDO_STATES} to Blast's npm dependencies"
    npm install --prefix "${BLAST_DIR}" --no-save --omit=dev "${PSEUDO_STATES}"
fi

echo "==> Publishing TEDI assets to public/vendor/tedi"
php artisan vendor:publish --tag=tedi-assets --force --ansi >/dev/null

echo "==> Serving the host app on ${HOST}:${PORT} (APP_URL=${APP_URL})"
php artisan serve --host="${HOST}" --port="${PORT}" &
SERVE_PID=$!

cleanup() {
    kill "${SERVE_PID}" 2>/dev/null || true
}
trap cleanup EXIT INT TERM

echo "==> Launching Storybook on http://localhost:6006"
php artisan blast:launch ${LAUNCH_ARGS[@]+"${LAUNCH_ARGS[@]}"}
