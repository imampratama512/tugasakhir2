#!/bin/bash

# ================================================
# promote-slave.sh - Promote MySQL Slave ke Master
# Jalankan di EC2 Oregon saat Virginia mati
# ================================================

echo "===== PROMOTE SLAVE → MASTER ====="

# 1. Stop replikasi
echo "[1/3] Stop slave replication..."
sudo mysql -e "STOP SLAVE;"
sudo mysql -e "RESET SLAVE ALL;"

# 2. Update .env Laravel pakai DB lokal
echo "[2/3] Update konfigurasi Laravel..."
cd /var/www/laravel-app
sudo sed -i 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env
php artisan config:cache

# 3. Restart service
echo "[3/3] Restart service..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

echo ""
echo "===== OREGON SEKARANG JADI MASTER ====="
echo "Website sudah berjalan dengan database Oregon"
echo "Cek: http://$(curl -s ifconfig.me)"