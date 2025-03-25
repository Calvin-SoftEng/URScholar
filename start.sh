#!/bin/sh
set -e

echo "Network and DNS Debugging..."
echo "Resolving database host..."
host db.wwecpxfveikhrbnsfuwf.supabase.co || true

echo "Checking internet connectivity..."
curl -v https://www.google.com || true

echo "Attempting to resolve database host IP..."
dig db.wwecpxfveikhrbnsfuwf.supabase.co || true

echo "Checking potential connection issues..."
nc -zv db.wwecpxfveikhrbnsfuwf.supabase.co 5432 || true

echo "Starting Laravel application..."
php -v
composer --version

# Laravel preparations
# php artisan config:clear
# php artisan cache:clear

# Detailed database connection check
php artisan tinker --execute="
    try {
        \$connection = DB::connection();
        \$pdo = \$connection->getPdo();
        echo 'Database connection successful!' . PHP_EOL;
        echo 'Database: ' . \$connection->getDatabaseName() . PHP_EOL;
    } catch (Exception \$e) {
        echo 'Database connection FAILED: ' . \$e->getMessage() . PHP_EOL;
        echo 'Connection Details:' . PHP_EOL;
        echo 'Host: ' . config('database.connections.pgsql.host') . PHP_EOL;
        echo 'Port: ' . config('database.connections.pgsql.port') . PHP_EOL;
        echo 'Database: ' . config('database.connections.pgsql.database') . PHP_EOL;
        exit(1);
    }
"

# Start services
php-fpm -R &
nginx -g 'daemon off;'