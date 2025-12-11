#!/bin/bash
set -e

# Run migrations
php artisan migrate --force

# Run seeder (ignore errors if already seeded)
php artisan db:seed --class=TestAccountsSeeder --force || true

# Start the server - PORT is provided by Railway as an integer
php artisan serve --host=0.0.0.0 --port="$PORT"
