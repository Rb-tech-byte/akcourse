#!/usr/bin/env bash
# ==============================================================================
# AK23 Course & Student Portal - Automated Production Deployment Script
# ==============================================================================

set -e

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CORE_DIR="$APP_DIR/core"

echo "======================================================================"
echo " Starting AK23 Production Deployment in: $APP_DIR"
echo "======================================================================"

cd "$APP_DIR"

# 1. Maintenance Mode
if [ -f "$CORE_DIR/artisan" ]; then
    echo "🚧 Entering maintenance mode..."
    php "$CORE_DIR/artisan" down --render="errors.503" || true
fi

# 2. Fetch latest changes from Git
echo "📥 Pulling latest git repository updates..."
git fetch origin main
git reset --hard origin/main

# 3. Install production Composer packages
echo "📦 Installing PHP composer dependencies (no-dev)..."
cd "$CORE_DIR"
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 4. Run database migrations
echo "🗄️ Executing database migrations..."
php artisan migrate --force

# 5. Clear and rebuild compiled caches
echo "⚡ Rebuilding Laravel caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Storage permissions
echo "🔒 Enforcing permissions on storage and bootstrap..."
chmod -R 775 "$CORE_DIR/storage" "$CORE_DIR/bootstrap/cache"

# 7. Restart background queues if configured
if command -v supervisorctl &> /dev/null; then
    echo "🔄 Restarting supervisor workers..."
    supervisorctl restart all || true
fi

# 8. Exit Maintenance Mode
echo "🌐 Bringing application back online..."
php artisan up

echo "======================================================================"
echo "🎉 Deployment successfully finished!"
echo "======================================================================"
