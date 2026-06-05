#!/bin/bash

# ================================================
# deploy.sh - Laravel + MySQL di EC2 (Tanpa RDS)
# Primary: N. Virginia | Secondary: Oregon
# Ubuntu 26.04 + PHP 8.5
# ================================================

set -e

echo "===== MULAI DEPLOYMENT LARAVEL ====="

# 1. Update sistem
echo "[1/10] Update sistem..."
sudo apt update && sudo apt upgrade -y

# 2. Install PHP 8.5
echo "[2/10] Install PHP 8.5..."
sudo apt install -y php8.5 php8.5-cli php8.5-fpm \
    php8.5-mysql php8.5-xml php8.5-curl \
    php8.5-mbstring php8.5-zip unzip

# 3. Install Nginx
echo "[3/10] Install Nginx..."
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# 4. Install MySQL
echo "[4/10] Install MySQL..."
sudo apt install -y mysql-server
sudo systemctl enable mysql
sudo systemctl start mysql

# 5. Install Composer
echo "[5/10] Install Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 6. Install Node.js + npm
echo "[6/10] Install Node.js..."
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# 7. Install Git & clone repo
echo "[7/10] Clone repository..."
sudo apt install -y git
cd /var/www
sudo git clone https://github.com/imampratama512/tugasakhir.git laravel-app

# Fix ownership
sudo chown -R ubuntu:ubuntu /var/www/laravel-app
git config --global --add safe.directory /var/www/laravel-app

cd /var/www/laravel-app

# 8. Install dependencies & build assets
echo "[8/10] Install dependencies & build assets..."
composer install --optimize-autoloader --no-dev
npm install
npm run build

# 9. Setup database MySQL
echo "[9/10] Setup database MySQL..."
echo ""
echo ">>> Masukkan nama database (contoh: dbtugasakhir):"
read DB_NAME
echo ">>> Masukkan username database (contoh: laravel_user):"
read DB_USER
echo ">>> Masukkan password database:"
read -s DB_PASS

sudo mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
sudo mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

echo "Database dan user berhasil dibuat!"

# 10. Setup environment
echo "[10/10] Setup environment..."
cp .env.example .env

# Otomatis isi nilai di .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s|APP_URL=.*|APP_URL=http://$(curl -s ifconfig.me)|" .env
sed -i "s/SESSION_DRIVER=.*/SESSION_DRIVER=database/" .env
sed -i "s/CACHE_DRIVER=.*/CACHE_DRIVER=database/" .env

echo ""
echo ">>> Cek .env sebelum lanjut, tekan CTRL+X lalu Y lalu ENTER setelah selesai..."
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