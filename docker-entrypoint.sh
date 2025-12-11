#!/bin/bash
set -e

# Run migrations
php artisan migrate --force

# Run seeder
php artisan db:seed --class=TestAccountsSeeder --force

# Start the server with the PORT as an integer
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
