#!/bin/bash

# ================================================
# deploy.sh - Laravel + MySQL di EC2 (Tanpa RDS)
# Primary: N. Virginia | Secondary: Oregon
# ================================================

set -e

echo "===== MULAI DEPLOYMENT LARAVEL ====="

# 1. Update sistem
echo "[1/9] Update sistem..."
sudo apt update && sudo apt upgrade -y

# 2. Install PHP 8.2
echo "[2/9] Install PHP 8.2..."
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-cli php8.2-fpm \
    php8.2-mysql php8.2-xml php8.2-curl \
    php8.2-mbstring php8.2-zip unzip

# 3. Install Nginx
echo "[3/9] Install Nginx..."
sudo apt install -y nginx
sudo systemctl enable nginx

# 4. Install MySQL
echo "[4/9] Install MySQL..."
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql

# 5. Install Composer
echo "[5/9] Install Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 6. Install Git & clone repo
echo "[6/9] Clone repository..."
sudo apt install -y git
cd /var/www
sudo git clone https://github.com/imampratama512/tugasakhir.git laravel-app
cd laravel-app

# 7. Install dependencies Laravel
echo "[7/9] Install dependencies..."
composer install --optimize-autoloader --no-dev

# 8. Setup environment
echo "[8/9] Setup environment..."
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

# 9. Setup permission & Nginx
echo "[9/9] Setup Nginx & permission..."
sudo chown -R www-data:www-data /var/www/laravel-app
sudo chmod -R 775 /var/www/laravel-app/storage
sudo chmod -R 775 /var/www/laravel-app/bootstrap/cache

sudo cp /var/www/laravel-app/nginx.conf /etc/nginx/sites-available/laravel
sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm

echo ""
echo "===== DEPLOYMENT SELESAI ====="
echo "Website bisa diakses di: http://$(curl -s ifconfig.me)"