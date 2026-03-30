# Dockerfile - PHP 8.4 FPM for Laravel 13
FROM php:8.4-fpm

# Arguments for noninteractive installs
ARG DEBIAN_FRONTEND=noninteractive

# System dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    unzip \
    zip \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath intl opcache zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*

# Install Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer files first for better cache
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts --prefer-dist

# Copy app source
COPY . .

# Ensure permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache || true

# Expose the HTTP port (Railway will route to this)
ENV PORT=8080
EXPOSE ${PORT}

# Default command: use PHP built-in server to serve the Laravel `public` directory.
# This is acceptable for simple container deploys (for production, use php-fpm + nginx/caddy).
CMD ["sh", "-lc", "php -S 0.0.0.0:${PORT} -t public public/index.php"]
