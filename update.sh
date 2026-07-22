#!/bin/bash
set -euo pipefail

echo "=========================================="
echo "      UPDATE APLIKASI LARAVEL"
echo "=========================================="

APP_DIR="/var/www/laravel-app"

cd "$APP_DIR"

# Tambahkan safe directory untuk Git
git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true
sudo git config --system --add safe.directory "$APP_DIR" 2>/dev/null || true

echo ""
echo ">>> Mengambil source terbaru..."
git pull origin main

echo ""
echo ">>> Install dependency Composer..."
composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

echo ""
read -p ">>> Jalankan migration? (y/n): " RUN_MIGRATE

if [[ "$RUN_MIGRATE" =~ ^[Yy]$ ]]; then
    echo ">>> Menjalankan migration..."
    php artisan migrate --force
fi

echo ""
echo ">>> Mengatur permission..."

sudo chown -R www-data:www-data "$APP_DIR/storage"
sudo chown -R www-data:www-data "$APP_DIR/bootstrap/cache"

sudo chmod -R 775 "$APP_DIR/storage"
sudo chmod -R 775 "$APP_DIR/bootstrap/cache"

echo ""
echo ">>> Membersihkan cache..."
php artisan optimize:clear

echo ""
echo ">>> Membuat cache baru..."
php artisan config:cache

# Route cache (abaikan jika gagal karena closure)
php artisan route:cache || echo ">>> Route cache dilewati (kemungkinan masih menggunakan Closure)."

php artisan view:cache

echo ""
echo ">>> Restart service..."

PHP_FPM=$(systemctl list-unit-files | awk '/php.*-fpm.service/ {print $1}' | head -1)

if [ -n "$PHP_FPM" ]; then
    sudo systemctl restart "$PHP_FPM"
    echo ">>> Restarted: $PHP_FPM"
else
    echo ">>> PHP-FPM tidak ditemukan."
fi

sudo systemctl reload nginx

echo ""
echo "=========================================="
echo "UPDATE BERHASIL"
echo "=========================================="