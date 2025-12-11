#!/bin/bash
set -e

echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear

echo "Running migrations..."
php artisan migrate --force

echo "Seeding database..."
php artisan db:seed --class=TestAccountsSeeder --force

echo "Starting server..."
php -S 0.0.0.0:$PORT -t public
