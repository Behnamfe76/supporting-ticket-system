FROM php:8.3-fpm

WORKDIR /var/www

# Install system dependencies in a single layer to reduce image size
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
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Redis and Swoole extensions
RUN pecl install redis && docker-php-ext-enable redis \
    && pecl install swoole && docker-php-ext-enable swoole

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy custom PHP configuration
COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# Install Node.js (for Laravel Mix/Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Create home directory for www-data and set up npm cache
RUN mkdir -p /home/www-data/.npm \
    && chown -R www-data:www-data /home/www-data \
    && chmod -R 750 /home/www-data \
    && npm config set cache /home/www-data/.npm --global

# Copy the entire application
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install
RUN npm run build

# Set permissions securely
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 750 /var/www/storage /var/www/bootstrap/cache

# Create Supervisor log directory and set permissions
RUN mkdir -p /var/log/supervisor \
    && chown -R www-data:www-data /var/log/supervisor \
    && chmod -R 750 /var/log/supervisor

# Copy and configure initialization script
COPY ./docker/init-laravel.sh /usr/local/bin/init-laravel.sh
RUN chmod +x /usr/local/bin/init-laravel.sh

# Run Laravel initialization at build time
RUN /usr/local/bin/init-laravel.sh

# Expose port for Octane
EXPOSE 8000

# Use non-root user for running the application
USER www-data

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
