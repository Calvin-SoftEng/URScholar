# Use the official PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www

# Install Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copy only necessary files first (to leverage Docker caching)
COPY composer.json composer.lock ./

# Install dependencies (before copying the whole project)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Now copy the rest of the application code
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port for Render (should match your Render service settings)
EXPOSE 10000

# Start PHP-FPM and nginx
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
