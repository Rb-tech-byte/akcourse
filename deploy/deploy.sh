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
rm -rf "$CORE_DIR/vendor/laramin"
rm -f "$CORE_DIR/bootstrap/cache/packages.php"
rm -f "$CORE_DIR/bootstrap/cache/services.php"
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

# 6. Storage, Assets, and Environment Permissions
echo "🔒 Enforcing secure permissions on storage, assets, and configuration..."
mkdir -p "$CORE_DIR/storage/framework/cache" \
         "$CORE_DIR/storage/framework/sessions" \
         "$CORE_DIR/storage/framework/views" \
         "$CORE_DIR/storage/logs" \
         "$CORE_DIR/bootstrap/cache" \
         "$APP_DIR/assets"

chmod -R 775 "$CORE_DIR/storage" "$CORE_DIR/bootstrap/cache" "$APP_DIR/assets"

if [ -f "$CORE_DIR/.env" ]; then
    chmod 644 "$CORE_DIR/.env"
fi

# 7. Restart background queues if configured
if command -v supervisorctl &> /dev/null; then
    echo "🔄 Restarting supervisor workers..."
    supervisorctl restart all || true
fi

# 8. Flush Web Server OPcache & LiteSpeed Cache
if command -v systemctl &> /dev/null; then
    echo "🔄 Refreshing web server & OPcache..."
    systemctl restart lsws 2>/dev/null || systemctl restart openlitespeed 2>/dev/null || true
fi

# 9. Exit Maintenance Mode
echo "🌐 Bringing application back online..."
php artisan up

echo "======================================================================"
echo "🎉 Deployment successfully finished!"
echo "======================================================================"
