#!/usr/bin/env bash
#
# Builds the static Storybook into public/storybook-static, for deployment.
#
# This is the deploy-time counterpart to start.sh. Where start.sh runs
# Storybook's dev server against a local `artisan serve`, this bakes a static
# UI that points at the deployed app.
#
# Important: the static build is NOT self-contained. .storybook/main.js uses
# @storybook/server-webpack5, so every story is rendered by an HTTP request to
# APP_URL/storybook_preview at view time — and the Controls addon re-fetches on
# every arg change. The Laravel app has to stay running behind it, and APP_URL
# is compiled into the bundle, so any change to it means rebuilding.
#
# Usage: ./build.sh
#
# Expects APP_URL to be the public URL of the deployed app.

set -euo pipefail

cd "$(dirname "$0")"

# --- APP_URL ------------------------------------------------------------
#
# Read from the environment first (that is how a PaaS supplies it), falling
# back to .env for a local test build.
if [ -z "${APP_URL:-}" ] && [ -f .env ]; then
    APP_URL="$(grep -E '^APP_URL=' .env | head -n1 | cut -d= -f2- | tr -d '"'"'"'"' | tr -d '\r')"
fi

if [ -z "${APP_URL:-}" ]; then
    echo "APP_URL is not set. It is compiled into the static build as the URL" >&2
    echo "the stories render against, so it must be the app's public URL." >&2
    exit 1
fi

# Export it so artisan sees it even when it came from this shell rather than
# .env — Dotenv does not overwrite variables already present in the environment,
# so an exported value wins, which is what a PaaS expects.
export APP_URL

echo "==> Building against APP_URL=${APP_URL}"

case "${APP_URL}" in
    *localhost*|*127.0.0.1*)
        echo "    Warning: that is a local URL. Stories in this build will only" >&2
        echo "    render on a machine where it resolves." >&2
        ;;
esac

# --- PHP dependencies ---------------------------------------------------
#
# A PaaS usually runs this itself before the build command. Guarded so the
# script also works on a bare checkout.
#
# composer.json declares a path repository pointing at ../, so the whole repo
# must be present and this must run with storybook/ as the working directory.
if [ ! -f vendor/autoload.php ]; then
    echo "==> Installing PHP dependencies"
    composer install --no-interaction --no-dev --optimize-autoloader
fi

# --- Blast's npm dependencies -------------------------------------------
#
# Same layering as start.sh, and for the same reason: .storybook/main.js loads
# storybook-addon-pseudo-states out of Blast's node_modules, which is the only
# tree where its @storybook/* peers resolve, but Blast owns that directory and
# prunes anything extraneous whenever it installs.
#
# blast:publish only runs its own `npm ci` when @storybook is missing or the
# installed version differs from blast.storybook_version (see
# Traits/Helpers.php::installDependencies). So install here, add the addon on
# top, and hand blast:publish a tree it will leave alone — which is also why
# --install must never be passed to it below.
BLAST_DIR="vendor/area17/blast"
PSEUDO_STATES="storybook-addon-pseudo-states@2.1.1"

if [ ! -d "${BLAST_DIR}/node_modules/@storybook" ]; then
    echo "==> Installing Blast's npm dependencies"
    (cd "${BLAST_DIR}" && npm ci --omit=dev --ignore-scripts)
fi

if [ ! -d "${BLAST_DIR}/node_modules/storybook-addon-pseudo-states" ]; then
    echo "==> Adding ${PSEUDO_STATES} to Blast's npm dependencies"
    npm install --prefix "${BLAST_DIR}" --no-save --omit=dev "${PSEUDO_STATES}"
fi

# --- TEDI's CSS and JS --------------------------------------------------
#
# Copies the package's dist/ into public/vendor/tedi, where config/blast.php
# points the story iframe. Needed at runtime, not build time, but this is the
# one place that is guaranteed to run on every deploy.
echo "==> Publishing TEDI assets to public/vendor/tedi"
php artisan vendor:publish --tag=tedi-assets --force --ansi

# --- The static build ---------------------------------------------------
#
# blast:publish regenerates the stories, runs `storybook build` inside Blast's
# directory, then copies the result to public/<output-dir>.
echo "==> Building the static Storybook"
#
# No --url here on purpose. It would *replace* config('blast.storybook_server_url')
# wholesale, and that config value is APP_URL with `/storybook_preview` appended
# — pass a bare APP_URL and every story fetches the site root instead of the
# preview route. Omitting the option makes blast:publish fall back to the config
# value, which derives the right URL from the APP_URL exported above.
php artisan blast:publish --output-dir=storybook-static --ansi

# Note: do NOT add `php artisan route:cache` here. Blast registers its
# /storybook_preview routes as closures (vendor/area17/blast/routes/web.php),
# and closure routes cannot be serialised — the command would fail the deploy.
# config:cache and view:cache are safe if you want them.

echo
echo "==> Done. Storybook is at ${APP_URL}/storybook-static/index.html"
