# Stage 1: Install PHP dependencies
FROM composer:2 AS deps

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-scripts

# Stage 2: Build frontend assets
FROM node:20 AS build

WORKDIR /app

# Copy PHP dependencies from previous stage
COPY --from=deps /app/vendor ./vendor

# Copy frontend files
COPY package.json package-lock.json vite.config.js ./
COPY resources/ ./resources/

RUN npm install && npm run build

# Stage 3: Final production image
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install zip pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Configure Apache
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html

# Copy from previous stages
COPY --from=deps /app/vendor /var/www/html/vendor
COPY --from=build /app/public/build /var/www/html/public/build

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

EXPOSE 80