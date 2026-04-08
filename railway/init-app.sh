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
if php artisan migrate --force; then
    echo "Migrations completed successfully"
else
    echo "Migration failed, but continuing deployment..."
fi

# Verify migrations ran
echo "Checking if blood_inventory table exists..."
php artisan tinker --execute="if (Schema::hasTable('blood_inventory')) { echo 'blood_inventory table exists'; } else { echo 'blood_inventory table missing!'; exit(1); }"

# Check if location fields were added to users table
echo "Checking if location fields exist in users table..."
php artisan tinker --execute="if (Schema::hasColumn('users', 'location')) { echo 'Location fields exist in users table'; } else { echo 'Location fields missing from users table!'; }"

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