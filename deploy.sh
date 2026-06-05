#!/bin/bash

# ================================================
# deploy.sh - Laravel + MySQL di EC2 (Tanpa RDS)
# Primary: N. Virginia | Secondary: Oregon
# Ubuntu 26.04 + PHP 8.5
# ================================================

set -e

echo "===== MULAI DEPLOYMENT LARAVEL ====="

# 1. Update sistem
echo "[1/8] Update sistem..."
sudo apt update && sudo apt upgrade -y

# 2. Install PHP 8.5
echo "[2/8] Install PHP 8.5..."
sudo apt install -y php8.5 php8.5-cli php8.5-fpm \
    php8.5-mysql php8.5-xml php8.5-curl \
    php8.5-mbstring php8.5-zip unzip

# 3. Install Nginx
echo "[3/8] Install Nginx..."
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# 4. Install MySQL
echo "[4/8] Install MySQL..."
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql

# 5. Install Composer
echo "[5/8] Install Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 6. Install Git & clone repo
echo "[6/8] Clone repository..."
sudo apt install -y git
cd /var/www
sudo git clone https://github.com/imampratama512/tugasakhir.git laravel-app
cd laravel-app

# 7. Install dependencies Laravel
echo "[7/8] Install dependencies..."
composer install --optimize-autoloader --no-dev

# 8. Setup environment
echo "[8/8] Setup environment..."
cp .env.example .env
echo ""
echo ">>> Silakan isi file .env sekarang"
echo ">>> Pastikan DB_HOST=127.0.0.1"
echo ">>> Tekan CTRL+X lalu Y lalu ENTER setelah selesai..."
sleep 2
nano .env

php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup permission & Nginx
sudo chown -R www-data:www-data /var/www/laravel-app
sudo chmod -R 775 /var/www/laravel-app/storage
sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache

sudo cp /var/www/laravel-app/nginx.conf /etc/nginx/sites-available/laravel
sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.5-fpm

echo ""
echo "===== DEPLOYMENT SELESAI ====="
echo "Website bisa diakses di: http://$(curl -s ifconfig.me)"