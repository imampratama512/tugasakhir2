#!/bin/bash

# ================================================
# setup-mysql-master.sh - Konfigurasi MySQL Master
# Jalankan di EC2 N. Virginia
# ================================================

set -e

echo "===== SETUP MYSQL MASTER (N. VIRGINIA) ====="

echo ">>> Nama database:"; read DB_NAME
echo ">>> Username Laravel:"; read DB_USER
echo ">>> Password:"; read -s DB_PASS
echo ""
echo ">>> Password replication user:"; read -s REPL_PASS
echo ""

sudo mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
sudo mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "CREATE USER IF NOT EXISTS 'repl_user'@'%' IDENTIFIED BY '${REPL_PASS}';"
sudo mysql -e "GRANT REPLICATION SLAVE ON *.* TO 'repl_user'@'%';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Fix bind-address di mysqld.cnf
sudo sed -i 's/bind-address.*= 127.0.0.1/bind-address = 0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf

sudo tee /etc/mysql/mysql.conf.d/master.cnf > /dev/null <<EOF
[mysqld]
server-id = 1
log_bin = /var/log/mysql/mysql-bin.log
binlog_do_db = ${DB_NAME}
bind-address = 0.0.0.0
EOF

sudo systemctl restart mysql
echo ""
echo "===== MASTER SIAP — CATAT INFO BERIKUT! ====="
sudo mysql -e "SHOW BINARY LOG STATUS\G"