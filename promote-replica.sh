#!/bin/bash
# Jalankan di EC2 Oregon saat RDS Primary Virginia tidak dapat diakses
 
echo "===== PROMOTE RDS READ REPLICA → STANDALONE ====="
echo "Masukkan RDS Read Replica identifier (nama, bukan endpoint):"; read REPLICA_ID
 
# Promote read replica via AWS CLI
aws rds promote-read-replica \
  --db-instance-identifier ${REPLICA_ID} \
  --region us-west-2
 
echo "Menunggu RDS selesai promote (biasanya 5-10 menit)..."
aws rds wait db-instance-available \
  --db-instance-identifier ${REPLICA_ID} \
  --region us-west-2
 
# Ambil endpoint baru setelah promote
NEW_HOST=$(aws rds describe-db-instances \
  --db-instance-identifier ${REPLICA_ID} \
  --region us-west-2 \
  --query 'DBInstances[0].Endpoint.Address' \
  --output text)
 
echo "Endpoint baru: ${NEW_HOST}"
 
# Update .env Laravel di Oregon
cd /var/www/laravel-app
sudo sed -i "s/DB_HOST=.*/DB_HOST=${NEW_HOST}/" .env
php artisan config:cache
sudo systemctl restart php8.5-fpm nginx
 
echo "===== OREGON SEKARANG TERHUBUNG KE DB STANDALONE ====="
echo "Website: http://$(curl -s ifconfig.me)"
