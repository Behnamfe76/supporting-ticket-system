FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis \
    && pecl install swoole \
    && docker-php-ext-enable swoole

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy custom PHP configuration
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Install Node.js (for Laravel Mix/Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Copy existing application directory contents
COPY . /var/www

# Install Node dependencies and build assets
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copy initialization script
COPY ./docker/init-laravel.sh /usr/local/bin/init-laravel.sh
RUN chmod +x /usr/local/bin/init-laravel.sh

# Run Laravel initialization at build time
RUN /usr/local/bin/init-laravel.sh

# Expose port for Octane
EXPOSE 8000

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
