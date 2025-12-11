#!/bin/bash
set -e

# Run migrations
php artisan migrate --force

# Run seeder (ignore errors if already seeded)
php artisan db:seed --class=TestAccountsSeeder --force || true

# Convert PORT to integer and start PHP's built-in server directly
# This bypasses Laravel's ServeCommand which has type issues
PORT_INT=$((PORT))
php -S 0.0.0.0:${PORT_INT} -t public
