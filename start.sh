#!/bin/sh
# Add more verbose logging
set -e  # Exit immediately if a command exits with a non-zero status

# Network and database connection debugging
echo "Checking network connectivity..."
ping -c 4 db.wwecpxfveikhrbnsfuwf.supabase.co
nslookup db.wwecpxfveikhrbnsfuwf.supabase.co
netstat -rn
ip addr

# Test PostgreSQL connection
PGPASSWORD=JaczM840OhZ5wUrN psql -h db.wwecpxfveikhrbnsfuwf.supabase.co -U postgres -d postgres -p 5432 -c "SELECT 1;"

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