#!/bin/bash
# Write Render env vars to a file so PHP can read them (Apache doesn't pass env to PHP)
if [ -n "${MYSQL_HOST}${MYSQLHOST}" ]; then
  {
    echo "MYSQL_HOST=${MYSQL_HOST:-$MYSQLHOST}"
    echo "MYSQL_USER=${MYSQL_USER:-$MYSQLUSER}"
    echo "MYSQL_PASSWORD=${MYSQL_PASSWORD:-$MYSQLPASSWORD}"
    echo "MYSQL_DATABASE=${MYSQL_DATABASE:-$MYSQLDATABASE}"
    echo "MYSQL_PORT=${MYSQL_PORT:-${MYSQLPORT:-3306}}"
  } > /var/www/html/config/render-env.ini 2>/dev/null || true
fi
exec apache2-foreground
