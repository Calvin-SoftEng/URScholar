FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Copy the application files
COPY . .

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Run Composer install
RUN composer install --optimize-autoloader --no-dev

# Expose port 80
EXPOSE 80

# Run the Laravel migration and start Apache
CMD php artisan migrate --force && apache2-foreground
