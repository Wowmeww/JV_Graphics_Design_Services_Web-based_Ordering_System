### Step 1: PHP + Composer (to install dependencies including Ziggy)
FROM php:8.2-fpm AS php-builder

WORKDIR /app

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of the app
COPY . .

# Generate app key
COPY .env.example .env
RUN php artisan key:generate

# Optional: start Reverb if needed (can be in runtime)
# RUN php artisan reverb:start


### Step 2: Node.js for frontend (Vite)
FROM node:18 AS node-builder

WORKDIR /app

# Copy only necessary files from PHP build stage
COPY --from=php-builder /app /app

# Install Node dependencies & build
RUN npm install
RUN npm run build


### Step 3: Final runtime container
FROM php:8.2-fpm

WORKDIR /var/www

# Install PHP extensions (same as before)
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy full app including vendor & built frontend
COPY --from=php-builder /app /var/www
COPY --from=node-builder /app/public/build /var/www/public/build

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
