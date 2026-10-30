#!/bin/sh
# O cliente MariaDB do wordpress:cli nao carrega caching_sha2_password.
mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "ALTER USER '${MYSQL_USER}'@'%' IDENTIFIED WITH mysql_native_password BY '${MYSQL_PASSWORD}';"
