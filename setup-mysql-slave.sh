#!/bin/bash

# ================================================
# setup-mysql-slave.sh - Konfigurasi MySQL Slave
# Jalankan di EC2 Oregon
# ================================================

set -e

echo "===== SETUP MYSQL SLAVE (OREGON) ====="

# 1. Buat database
echo "[1/4] Buat database..."
sudo mysql -e "CREATE DATABASE IF NOT EXISTS dbtugasakhir;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'laravel_user'@'localhost' IDENTIFIED BY 'imam1976';"
sudo mysql -e "GRANT ALL PRIVILEGES ON dbtugasakhir.* TO 'laravel_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# 2. Konfigurasi MySQL sebagai slave
echo "[2/4] Konfigurasi MySQL slave..."
sudo tee /etc/mysql/mysql.conf.d/slave.cnf > /dev/null <<EOF
[mysqld]
server-id = 2
log_bin = /var/log/mysql/mysql-bin.log
binlog_do_db = dbtugasakhir
bind-address = 0.0.0.0
relay-log = /var/log/mysql/mysql-relay-bin.log
EOF

# 3. Restart MySQL & mulai replikasi
echo "[3/4] Start replikasi..."
sudo systemctl restart mysql
sudo mysql -e "
CHANGE REPLICATION SOURCE TO
  SOURCE_HOST='54.159.4.173',
  SOURCE_USER='repl_user',
  SOURCE_PASSWORD='imam1976',
  SOURCE_LOG_FILE='mysql-bin.000001',
  SOURCE_LOG_POS=158;
"
sudo mysql -e "START REPLICA;"

# 4. Cek status replikasi
echo "[4/4] Cek status replikasi..."
sudo mysql -e "SHOW REPLICA STATUS\G" | grep -E "Replica_IO_Running|Replica_SQL_Running|Seconds_Behind_Source"

echo ""
echo "===== SLAVE SIAP ====="
echo "Pastikan Replica_IO_Running: Yes dan Replica_SQL_Running: Yes"