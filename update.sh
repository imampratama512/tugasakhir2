#!/bin/bash

echo "===== UPDATE APLIKASI ====="

cd /var/www/laravel-app || exit 1

git pull origin main

composer install --no-dev --optimize-autoloader --no-interaction

php artisan migrate --force

php artisan optimize:clear
php artisan optimize

sudo systemctl restart php8.5-fpm

echo "===== UPDATE SELESAI ====="