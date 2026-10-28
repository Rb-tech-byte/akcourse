#!/usr/bin/env bash
# ==============================================================================
# AK23 / Maarifa23 Automated Production Deployment Script
# Target Server: /home/m23.ak23studiokits.com
# Git: Public HTTPS (https://github.com/Rb-tech-byte/akcourse.git) - No SSH key required
# ==============================================================================

set -e
export COMPOSER_ALLOW_SUPERUSER=1

# 1. Determine Application Root Directory (CyberPanel uses public_html as document root)
if [ -d "/home/m23.ak23studiokits.com/public_html/core" ]; then
    APP_DIR="/home/m23.ak23studiokits.com/public_html"
elif [ -d "/home/m23.ak23studiokits.com/public_html" ]; then
    APP_DIR="/home/m23.ak23studiokits.com/public_html"
elif [ -d "/home/m23.ak23studiokits.com/core" ]; then
    APP_DIR="/home/m23.ak23studiokits.com"
elif [ -d "core" ] && [ -d "assets" ]; then
    APP_DIR="$(pwd)"
elif [ -d "../core" ] && [ -d "../assets" ]; then
    APP_DIR="$(cd .. && pwd)"
else
    APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
fi

CORE_DIR="$APP_DIR/core"

echo "======================================================================"
echo " Starting Maarifa23 Production Deployment in: $APP_DIR"
echo "======================================================================"

cd "$APP_DIR"

# 2. Ensure public HTTPS Git remote (no SSH key or login required)
GIT_REPO="https://github.com/Rb-tech-byte/akcourse.git"
if [ -d ".git" ]; then
    echo "🔗 Ensuring public HTTPS remote is active..."
    git remote set-url origin "$GIT_REPO" 2>/dev/null || git remote add origin "$GIT_REPO" 2>/dev/null || true
fi

# 3. Enter Maintenance Mode (if artisan exists)
if [ -f "$CORE_DIR/artisan" ]; then
    echo "🚧 Entering maintenance mode..."
    php "$CORE_DIR/artisan" down --render="errors.503" || true
fi

# 4. Fetch latest changes from public GitHub repository
if [ -d ".git" ]; then
    echo "📥 Pulling latest codebase from GitHub (main)..."
    git fetch origin main
    git reset --hard origin/main
fi

# 5. Synchronize .env and database configurations
if [ -f "$APP_DIR/deploy/init_env_db.php" ]; then
    echo "⚙️ Syncing environment and database settings..."
    php "$APP_DIR/deploy/init_env_db.php"
fi

# 6. Locate Composer binary
COMPOSER_BIN="composer"
if ! command -v composer &> /dev/null; then
    if [ -f "/usr/local/bin/composer" ]; then
        COMPOSER_BIN="/usr/local/bin/composer"
    elif [ -f "/usr/bin/composer" ]; then
        COMPOSER_BIN="/usr/bin/composer"
    elif [ -f "$CORE_DIR/composer.phar" ]; then
        COMPOSER_BIN="php $CORE_DIR/composer.phar"
    fi
fi

# 7. Install production Composer dependencies
echo "📦 Installing PHP composer dependencies (no-dev)..."
cd "$CORE_DIR"
rm -rf "$CORE_DIR/vendor/laramin"
rm -f "$CORE_DIR/bootstrap/cache/packages.php"
rm -f "$CORE_DIR/bootstrap/cache/services.php"
$COMPOSER_BIN install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 8. Generate application key if missing
if ! grep -q "APP_KEY=base64:" "$CORE_DIR/.env" 2>/dev/null; then
    echo "🔑 Generating application encryption key..."
    php artisan key:generate --force
fi

# 9. Execute database migrations
echo "🗄️ Executing database migrations..."
php artisan migrate --force

# 10. Rebuild optimized Laravel caches
echo "⚡ Rebuilding Laravel caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 11. Enforce directory permissions & file security
echo "🔒 Enforcing secure permissions on storage, assets, and config..."
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
if [ -f "$APP_DIR/index.php" ]; then
    chmod 644 "$APP_DIR/index.php"
fi
if [ -f "$APP_DIR/.htaccess" ]; then
    chmod 644 "$APP_DIR/.htaccess"
fi

# Auto-detect website system user and set ownership
SITE_USER=$(stat -c '%U' "$APP_DIR" 2>/dev/null || echo "")
if [ -n "$SITE_USER" ] && [ "$SITE_USER" != "root" ]; then
    echo "👤 Restoring site ownership to user: $SITE_USER"
    chown -R "${SITE_USER}:${SITE_USER}" "$APP_DIR" 2>/dev/null || true
fi

# 12. Restart supervisor workers if active
if command -v supervisorctl &> /dev/null; then
    echo "🔄 Restarting background queue workers..."
    supervisorctl restart all || true
fi

# 13. Refresh web server cache (OpenLiteSpeed / LiteSpeed / Nginx)
if command -v systemctl &> /dev/null; then
    echo "🔄 Refreshing web server cache..."
    systemctl reload lsws 2>/dev/null || systemctl restart lsws 2>/dev/null || systemctl reload nginx 2>/dev/null || true
fi

# 14. Bring application back online
echo "🌐 Bringing application online..."
php artisan up

echo "======================================================================"
echo "🎉 Maarifa23 Deployment Finished Successfully!"
echo " Portal: https://m23.ak23studiokits.com"
echo "======================================================================"
