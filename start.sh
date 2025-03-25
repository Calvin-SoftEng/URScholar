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

# Generate application key if not set
php artisan key:generate

# Check database connection with verbose output
echo "Checking database connection..."
php artisan tinker --execute="
    try {
        \$connection = DB::connection();
        \$pdo = \$connection->getPdo();
        echo 'Database connection successful!' . PHP_EOL;
        echo 'Database: ' . \$connection->getDatabaseName() . PHP_EOL;
    } catch (Exception \$e) {
        echo 'Database connection failed: ' . \$e->getMessage() . PHP_EOL;
        exit(1);
    }
"

# Run migrations with verbose output
php artisan migrate --force --verbose

# Output routing information
php artisan route:list

# Start PHP-FPM in the background with more logging
php-fpm -R &

# Start Nginx in the foreground
nginx -g 'daemon off;'