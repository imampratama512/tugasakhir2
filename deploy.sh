#!/bin/bash
set -e
echo "===== MULAI DEPLOYMENT LARAVEL (RDS) ====="
 
# 1. Update sistem
sudo apt update && sudo apt upgrade -y
 
# 2. Install PHP 8.5
sudo apt install -y php8.5 php8.5-cli php8.5-fpm \
    php8.5-mysql php8.5-xml php8.5-curl php8.5-mbstring php8.5-zip unzip
 
# 3. Install Nginx
sudo apt install -y nginx
sudo systemctl enable nginx && sudo systemctl start nginx
 
# MySQL client saja (untuk tes koneksi ke RDS, tanpa mysql-server)
sudo apt install -y mysql-client
 
# 4. Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
 
# 5. Install Git & clone repo
sudo apt install -y git
cd /var/www
sudo git clone https://github.com/imampratama512/tugasakhir2.git laravel-app
sudo chown -R ubuntu:ubuntu /var/www/laravel-app
git config --global --add safe.directory /var/www/laravel-app
cd /var/www/laravel-app
 
# 6. Install dependencies
composer install --optimize-autoloader --no-dev
 
# 7. Setup .env — arahkan ke RDS endpoint
echo ">>> Masukkan RDS endpoint (dari AWS Console):"; read RDS_HOST
echo ">>> Masukkan nama database:"; read DB_NAME
echo ">>> Masukkan username:"; read DB_USER
echo ">>> Masukkan password:"; read -s DB_PASS
 
cp .env.example .env
sed -i "s/DB_HOST=.*/DB_HOST=${RDS_HOST}/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s|APP_URL=.*|APP_URL=http://$(curl -s ifconfig.me)|" .env
nano .env
 
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
 
# 8. Permission & Nginx
sudo chown -R www-data:www-data /var/www/laravel-app
sudo chmod -R 775 /var/www/laravel-app/storage
sudo cp nginx.conf /etc/nginx/sites-available/laravel
sudo ln -sf /etc/nginx/sites-available/laravel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl restart nginx && sudo systemctl restart php8.5-fpm
 
echo "===== DEPLOYMENT SELESAI ====="
echo "Website: http://$(curl -s ifconfig.me)"
