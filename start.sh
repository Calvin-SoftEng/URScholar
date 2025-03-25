#!/bin/sh
# Add more verbose logging
set -e  # Exit immediately if a command exits with a non-zero status

# Removed network diagnostic commands that require root/privileged access
echo "Checking database connectivity..."
# Use a method that doesn't require special permissions
echo "Attempting to resolve database host..."
url postgresql://postgres.wwecpxfveikhrbnsfuwf:nFMbX1OYdOwOHAMB@aws-0-ap-southeast-1.pooler.supabase.com:5432/postgres

echo "Starting Laravel application..."
php -v
composer --version
nginx -v

# Detailed Laravel preparation
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# More verbose migration with additional error handling
php artisan migrate --force --verbose || (
    echo "Migration failed. Checking database connection..." &&
    php artisan tinker --execute="
        try {
            DB::connection()->getPdo();
            echo 'Database connection successful';
        } catch (Exception \$e) {
            echo 'Database connection failed: ' . \$e->getMessage();
        }
    "
)

# Output any potential routing or configuration issues
php artisan route:list
php artisan config:show

# Verify application key
php artisan key:generate --show

# Start PHP-FPM in the background with more logging
php-fpm -R &

# Start Nginx in the foreground
nginx -g 'daemon off;'