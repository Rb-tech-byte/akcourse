#!/usr/bin/env bash
# ==============================================================================
# Maarifa23 / AK23 - Full Server Automated Setup & Production Deployer
# ==============================================================================

set -e

# 1. Determine Application Root Directory
if [ -d "core" ] && [ -d "assets" ]; then
    APP_DIR="$(pwd)"
elif [ -d "../core" ] && [ -d "../assets" ]; then
    APP_DIR="$(cd .. && pwd)"
elif [ -d "/home/m23.ak23studiokits.com/public_html/core" ]; then
    APP_DIR="/home/m23.ak23studiokits.com/public_html"
else
    APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
fi

CORE_DIR="$APP_DIR/core"
echo "======================================================================"
echo " Starting Maarifa23 Automated Deployment in: $APP_DIR"
echo "======================================================================"

cd "$APP_DIR"

# 2. Configure .env and Initialize Database
echo "⚙️ Configuring environment (.env) and initializing database..."
php "$APP_DIR/deploy/init_env_db.php"

# 3. Find Composer binary
COMPOSER_BIN="composer"
if ! command -v composer &> /dev/null; then
    if [ -f "/usr/local/bin/composer" ]; then
        COMPOSER_BIN="/usr/local/bin/composer"
    elif [ -f "/usr/bin/composer" ]; then
        COMPOSER_BIN="/usr/bin/composer"
    fi
fi

# 4. Install PHP Dependencies
echo "📦 Installing PHP composer dependencies (no-dev)..."
cd "$CORE_DIR"
$COMPOSER_BIN install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 5. Generate Application Key if not set
if ! grep -q "APP_KEY=base64:" "$CORE_DIR/.env"; then
    echo "🔑 Generating application encryption key..."
    php artisan key:generate --force
fi

# 6. Execute any pending Laravel database migrations
echo "🗄️ Executing database migrations..."
php artisan migrate --force

# 7. Rebuild Optimized Production Caches
echo "⚡ Rebuilding Laravel caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Directory Permissions & Security Lockdown
echo "🔒 Enforcing secure permissions on storage, assets, and configuration..."
mkdir -p "$CORE_DIR/storage/framework/cache" \
         "$CORE_DIR/storage/framework/sessions" \
         "$CORE_DIR/storage/framework/views" \
         "$CORE_DIR/storage/logs" \
         "$CORE_DIR/bootstrap/cache" \
         "$APP_DIR/assets"

chmod -R 775 "$CORE_DIR/storage" "$CORE_DIR/bootstrap/cache" "$APP_DIR/assets"
chmod 600 "$CORE_DIR/.env"
chmod 644 "$APP_DIR/index.php" "$APP_DIR/.htaccess"
chmod 755 "$APP_DIR"

# Auto-detect website system user and set ownership
if [ -d "$APP_DIR" ]; then
    SITE_USER=$(stat -c '%U' "$APP_DIR" 2>/dev/null || echo "")
    if [ -n "$SITE_USER" ] && [ "$SITE_USER" != "root" ]; then
        echo "👤 Restoring site ownership to user: $SITE_USER"
        chown -R "${SITE_USER}:${SITE_USER}" "$APP_DIR"
    fi
fi

# Reload OpenLiteSpeed web server if installed to read updated .htaccess rules
if command -v systemctl &> /dev/null && systemctl is-active --quiet lsws; then
    echo "🔄 Reloading OpenLiteSpeed web server (lsws)..."
    systemctl reload lsws 2>/dev/null || systemctl restart lsws 2>/dev/null || true
fi

echo "======================================================================"
echo " ✅ Maarifa23 Deployment Completed Successfully!"
echo " Portal URL: https://m23.ak23studiokits.com"
echo "======================================================================"
