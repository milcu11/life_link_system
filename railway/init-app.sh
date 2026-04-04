#!/bin/bash

# Run database migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if needed
php artisan storage:link

echo "App initialization complete"