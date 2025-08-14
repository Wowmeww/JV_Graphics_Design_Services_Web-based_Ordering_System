# Dockerfile
FROM php:8.2-apache

WORKDIR /var/www/html

# Enable Apache mod_rewrite for Laravel
RUN a2enmod rewrite

# Install required PHP extensions
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    zip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl pdo_mysql zip pcntl

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set Apache document root to Laravel's public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN apt-get update && apt-get install -y supervisor


EXPOSE 80
