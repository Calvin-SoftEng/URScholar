#!/bin/sh
# Add more verbose logging
set -e  # Exit immediately if a command exits with a non-zero status

echo "Starting Laravel application..."
php -v
composer --version
nginx -v

# Detailed Laravel preparation
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# More verbose migration
php artisan migrate --force --verbose

# Output any potential routing or configuration issues
php artisan route:list
php artisan config:show

# Check for any specific Laravel errors
php artisan tinker --execute="dd(config('app.key'));"

# Start PHP-FPM in the background with more logging
php-fpm -R &

# Start Nginx in the foreground
nginx -g 'daemon off;'