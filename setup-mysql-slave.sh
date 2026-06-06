#!/bin/bash
set -e

echo "===== SETUP MYSQL SLAVE (OREGON) ====="

# Isi nilai ini dari output setup-mysql-master.sh
MASTER_IP="54.159.4.173"
MASTER_LOG_FILE="mysql-bin.000003"
MASTER_LOG_POS="158"

echo ">>> Nama database:"; read DB_NAME
echo ">>> Username Laravel:"; read DB_USER
echo ">>> Password:"; read -s DB_PASS
echo ""
echo ">>> Password replication:"; read -s REPL_PASS
echo ""

sudo mysql -e "CREATE DATABASE IF NOT EXISTS ${DB_NAME};"
sudo mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
sudo mysql -e "GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

sudo tee /etc/mysql/mysql.conf.d/slave.cnf > /dev/null <<EOF
[mysqld]
server-id = 2
log_bin = /var/log/mysql/mysql-bin.log
binlog_do_db = ${DB_NAME}
bind-address = 0.0.0.0
relay-log = /var/log/mysql/mysql-relay-bin.log
EOF

sudo systemctl restart mysql

sudo mysql -e "STOP REPLICA;"
sudo mysql -e "RESET REPLICA ALL;"
sudo mysql -e "
CHANGE REPLICATION SOURCE TO
  SOURCE_HOST='${MASTER_IP}',
  SOURCE_USER='repl_user',
  SOURCE_PASSWORD='${REPL_PASS}',
  SOURCE_LOG_FILE='${MASTER_LOG_FILE}',
  SOURCE_LOG_POS=${MASTER_LOG_POS};
"
sudo mysql -e "START REPLICA;"

echo ""
echo "===== SLAVE SIAP ====="
sudo mysql -e "SHOW REPLICA STATUS\G" | grep -E "Replica_IO_Running|Replica_SQL_Running|Seconds_Behind_Source"