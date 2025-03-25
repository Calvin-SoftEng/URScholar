#!/bin/sh

# Print environment and configuration info
echo "Starting Laravel application..."
php -v
composer --version
nginx -v

# Run Laravel specific preparations
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Check for any Laravel-specific errors
php artisan route:list

# Start PHP-FPM in the background
php-fpm &

# Start Nginx in the foreground
nginx -g 'daemon off;'