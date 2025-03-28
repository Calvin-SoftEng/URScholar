# Use the official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    postgresql-client \
    host \
    iputils-ping \
    net-tools \
    dnsutils \
    wget \
    curl \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Set permissions more comprehensively
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Add these lines to help diagnose
RUN php artisan config:clear
# RUN php artisan cache:clear
RUN php artisan key:generate

# Copy nginx and php-fpm configurations
COPY nginx.conf /etc/nginx/nginx.conf
COPY www.conf /etc/php-fpm.d/www.conf

# Prepare startup script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port for Render
EXPOSE 10000

# Use the startup script
CMD ["/start.sh"]