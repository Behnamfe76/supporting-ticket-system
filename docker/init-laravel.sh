#!/bin/sh
set -ex

cd /var/www

# Create .env from .env.example if it does not exist
if [ ! -f .env ]; then
  cp .env.example .env
fi

# Generate application key if not set
php artisan key:generate

# Install PHP dependencies
composer install --no-interaction --prefer-dist --optimize-autoloader

# Cache config and routes
php artisan config:cache
php artisan route:cache

# Create storage symlink
php artisan storage:link || true

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Set permissions (optional, for local dev)
chown -R www-data:www-data storage bootstrap/cache || true
