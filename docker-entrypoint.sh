#!/bin/bash
# Write Render env vars to a file so PHP can read them (Apache doesn't pass env to PHP)
# Supports both MYSQL_* and DB_* naming
H="${MYSQL_HOST:-${MYSQLHOST:-$DB_HOST}}"
U="${MYSQL_USER:-${MYSQLUSER:-$DB_USERNAME}}"
P="${MYSQL_PASSWORD:-${MYSQLPASSWORD:-$DB_PASSWORD}}"
D="${MYSQL_DATABASE:-${MYSQLDATABASE:-$DB_DATABASE}}"
O="${MYSQL_PORT:-${MYSQLPORT:-${DB_PORT:-3306}}}"
if [ -n "$H" ] && [ -n "$U" ] && [ -n "$D" ]; then
  {
    echo "MYSQL_HOST=$H"
    echo "MYSQL_USER=$U"
    echo "MYSQL_PASSWORD=$P"
    echo "MYSQL_DATABASE=$D"
    echo "MYSQL_PORT=$O"
  } > /var/www/html/config/render-env.ini 2>/dev/null || true
fi
exec apache2-foreground
