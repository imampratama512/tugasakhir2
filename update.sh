#!/bin/bash
set -e
 
echo "===== MULAI UPDATE APLIKASI LARAVEL ====="
 
cd /var/www/laravel-app
 
# 1. Ambil kode terbaru dari GitHub
sudo git pull origin main
 
# 2. Update dependencies PHP
composer install --optimize-autoloader --no-dev
 
# 3. Jalankan migration baru (jika ada)
echo ">>> Jalankan migration baru? (y/n)"; read RUN_MIGRATE
if [ "$RUN_MIGRATE" = "y" ]; then
  php artisan migrate --force
fi
 
# 4. Build ulang cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
 
# 5. Set ulang permission (jaga-jaga ada file baru dari git pull)
sudo chown -R www-data:www-data /var/www/laravel-app
sudo chmod -R 775 /var/www/laravel-app/storage
 
# 6. Restart service
sudo systemctl restart php8.5-fpm
sudo systemctl reload nginx
 
echo "===== UPDATE SELESAI ====="
