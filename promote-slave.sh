#!/bin/bash

# ================================================
# promote-slave.sh - Promote MySQL Slave ke Master
# Jalankan di EC2 Oregon saat Virginia mati
# ================================================

echo "===== PROMOTE SLAVE → MASTER ====="

sudo mysql -e "STOP REPLICA;"
sudo mysql -e "RESET REPLICA ALL;"

cd /var/www/laravel-app
sudo sed -i 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env
php artisan config:cache

sudo systemctl restart php8.5-fpm
sudo systemctl restart nginx

echo ""
echo "===== OREGON SEKARANG JADI MASTER ====="
echo "Website: http://$(curl -s ifconfig.me)"