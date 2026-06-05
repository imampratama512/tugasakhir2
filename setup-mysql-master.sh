#!/bin/bash

# ================================================
# setup-mysql-master.sh - Konfigurasi MySQL Master
# Jalankan di EC2 N. Virginia
# ================================================

set -e

echo "===== SETUP MYSQL MASTER (N. VIRGINIA) ====="

# 1. Buat user untuk Laravel dan user replikasi
echo "[1/4] Buat user MySQL..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS dbtugasakhir;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'imam1976';"
sudo mysql -e "GRANT ALL PRIVILEGES ON dbtugasakhir.* TO 'laravel_user'@'localhost';"
sudo mysql -e "CREATE USER IF NOT EXISTS 'repl_user'@'%' IDENTIFIED BY 'imam1976';"
sudo mysql -e "GRANT REPLICATION SLAVE ON *.* TO 'repl_user'@'%';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 2. Konfigurasi MySQL sebagai master
echo "[2/4] Konfigurasi MySQL master..."
sudo tee /etc/mysql/mysql.conf.d/master.cnf > /dev/null <<EOF
[mysqld]
server-id = 1
log_bin = /var/log/mysql/mysql-bin.log
binlog_do_db = dbtugasakhir
bind-address = 0.0.0.0
EOF

# 3. Restart MySQL
echo "[3/4] Restart MySQL..."
sudo systemctl restart mysql

# 4. Tampilkan info master (dibutuhkan untuk setup slave)
echo "[4/4] Info Master (CATAT INI!)..."
sudo mysql -e "SHOW MASTER STATUS\G"

echo ""
echo "===== MASTER SIAP ====="
echo "Catat nilai 'File' dan 'Position' di atas!"
echo "Nanti dibutuhkan saat setup slave di Oregon"