#!/bin/bash
set -e

echo "===== MULAI UPDATE APLIKASI LARAVEL ====="

APP_DIR="/var/www/laravel-app"
cd "$APP_DIR"

# Izinkan git di folder ini (user ubuntu & root)
git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true
sudo git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true

# 1. Ambil kode terbaru — sementara ubah owner ke ubuntu agar git pull tidak error
sudo chown -R ubuntu:ubuntu "$APP_DIR"
git pull origin main

# 2. Update dependencies PHP (jangan jalankan sebagai root)
composer install --optimize-autoloader --no-dev

# 3. Jalankan migration baru (jika ada)
echo ">>> Jalankan migration baru? (y/n)"
read RUN_MIGRATE
if [ "$RUN_MIGRATE" = "y" ]; then
  php artisan migrate --force
fi

# 4. Bersihkan cache lama dulu, lalu buat ulang
php artisan optimize:clear
php artisan config:cache
php artisan view:cache

# 5. Kembalikan ownership ke www-data (Nginx/PHP-FPM butuh akses write ke storage & bootstrap/cache)
sudo chown -R www-data:www-data "$APP_DIR"
sudo chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

# 6. Restart service — deteksi versi PHP-FPM yang terinstall
PHP_FPM=$(systemctl list-units --type=service --all 2>/dev/null | grep -oE 'php[0-9.]+-fpm' | head -1)
if [ -n "$PHP_FPM" ]; then
  sudo systemctl restart "$PHP_FPM"
  echo ">>> Restarted: $PHP_FPM"
else
  echo ">>> PERINGATAN: PHP-FPM tidak ditemukan. Cek dengan: systemctl list-units | grep php"
fi
sudo systemctl reload nginx

echo "===== UPDATE SELESAI ====="
