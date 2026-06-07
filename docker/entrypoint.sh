#!/bin/bash
# =============================================================================
# entrypoint.sh — Alumni Association Container Startup Script
# =============================================================================
#
# This script runs every time the container starts (NOT during image build).
# It handles runtime initialization that cannot be baked into the image
# because it depends on environment variables or runtime state.
#
# Order of operations:
#   1. Ensure storage directory structure exists
#   2. Fix file permissions for www-data
#   3. Create public/storage symlink (storage:link)
#   4. Cache configuration, routes, views, and events for production
#   5. Optionally run database migrations (if RUN_MIGRATIONS=true)
#   6. Start Apache as the foreground process
# =============================================================================

set -e

echo "=========================================="
echo " Alumni Association — Container Starting"
echo "=========================================="

# ---------------------------------------------------------------------------
# 1. Ensure storage directory structure exists
# ---------------------------------------------------------------------------
# These directories may not exist in the image if they were empty in git
# (git doesn't track empty directories). Laravel needs all of them.
# ---------------------------------------------------------------------------
echo "[1/6] Ensuring storage directory structure..."
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

if [ "${DB_CONNECTION}" = "sqlite" ]; then
    echo "Using SQLite: Ensuring database.sqlite exists..."
    touch database/database.sqlite
fi

# ---------------------------------------------------------------------------
# 2. Fix permissions
# ---------------------------------------------------------------------------
# The web server (Apache) runs as www-data and needs write access to storage
# (for logs, cache, sessions, file uploads) and bootstrap/cache (for cached
# config/routes/views/events).
# ---------------------------------------------------------------------------
echo "[2/6] Setting file permissions..."
chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

# ---------------------------------------------------------------------------
# 3. Create the public/storage -> storage/app/public symlink
# ---------------------------------------------------------------------------
# Required for serving uploaded files (alumni photos, event media, etc.).
# The --force flag ensures it recreates the link if it already exists.
# This is idempotent and safe to run on every startup.
# ---------------------------------------------------------------------------
echo "[3/6] Creating storage symlink..."
php artisan storage:link --force

# ---------------------------------------------------------------------------
# 4. Cache configuration for production performance
# ---------------------------------------------------------------------------
# These commands compile config/routes/views/events into cached files,
# eliminating the need to parse them on every request. This is critical
# for production performance.
#
# IMPORTANT: config:cache reads environment variables and bakes them into
# the cached config. This is why APP_KEY and other secrets must be set as
# environment variables BEFORE the container starts.
# ---------------------------------------------------------------------------
echo "[4/6] Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# ---------------------------------------------------------------------------
# 5. Optionally run database migrations
# ---------------------------------------------------------------------------
# Only runs if RUN_MIGRATIONS=true is set in environment variables.
# This is OFF by default for safety — you must explicitly enable it.
#
# Recommended workflow:
#   - Set RUN_MIGRATIONS=true for the first deployment
#   - After successful deployment, set it to false (or leave true if you
#     want auto-migrations on every deploy)
#   - The --force flag is required to run migrations in production
# ---------------------------------------------------------------------------
if [ "${RUN_MIGRATIONS}" = "true" ]; then
    echo "[5/6] Running database migrations..."
    php artisan migrate --force
else
    echo "[5/6] Skipping migrations (RUN_MIGRATIONS is not 'true')"
fi

# ---------------------------------------------------------------------------
# 6. Start Apache in the foreground
# ---------------------------------------------------------------------------
# exec replaces this shell process with Apache, making it PID 1.
# This ensures that Docker/Render can properly send signals (SIGTERM, etc.)
# to Apache for graceful shutdown.
# ---------------------------------------------------------------------------
echo "[6/6] Starting Apache..."
echo "=========================================="
exec apache2-foreground
