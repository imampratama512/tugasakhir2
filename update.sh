#!/bin/bash
# update.sh - Jalankan ini setiap kali ada perubahan code

echo "===== UPDATE APLIKASI ====="

cd /var/www/laravel-app

sudo git pull origin main
sudo composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

sudo systemctl restart php8.2-fpm

echo "===== UPDATE SELESAI ====="