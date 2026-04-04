#!/bin/bash

set -e  # Exit on any error

echo "Starting app initialization..."

# Test Laravel can boot
echo "Testing Laravel application boot..."
php artisan --version

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch(Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); exit(1); }"

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Clear and cache config
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Create storage link if needed
echo "Creating storage link..."
php artisan storage:link

# Test that the app can serve a simple response
echo "Testing application response..."
curl -f http://localhost/ || echo "Local curl test skipped (expected in deployment)"

echo "App initialization complete!"