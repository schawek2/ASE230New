#!/bin/bash

# Based off module2/code/1_laravel/6.model and controllers/run1-6.sh
# and module2/code/1_laravel/7. views and controllers/run1-7.sh

TARGET_DIR="music-api"
DB_NAME="music_db"
DB_USER="root"
DB_PASSWORD="Rustycat"

set -e

echo "***Checking MySQL is running***"

if ! sudo service mysql status > /dev/null 2>&1; then
	echo "***MySQL not running***"
	sudo service mysql start
fi

echo "MySQL is running"

DB_EXISTS=$(mysql -u$DB_USER -p$DB_PASSWORD -e "SHOW DATABASES LIKE '$DB_NAME';" 2>/dev/null | grep "$DB_NAME" || true)

if [ -z "$DB_EXISTS" ]; then
	echo "Database '$DB_NAME' does not exist."
	mysql -u$DB_USER -p$DB_PASSWORD -e "CREATE DATABASE $DB_NAME;"
else
	echo "Database '$DB_NAME' already exists."
fi

echo "***Moving to laravel project directory***"

cd "$TARGET_DIR"

if [ $? -ne 0 ]; then
	echo "ERROR: Could not enter directory"
	exit 1
fi

echo "Now inside directory"

echo "***Running laravel migrations***"

php artisan migrate --force

echo "***Clearing laravel cache and optimizing***"

php artisan config:clear
php artisan optimize:clear
php artisan optimize

echo "***Starting laravel deployment server***"

php artisan serve --host=0.0.0.0 --port=8000 &

SERVER_PID=$!

echo "Laravel server started with PID $SERVER_PID"
echo "See API at http://localhost:8000/api"

echo "To stop server run: kill $SERVER_PID"

