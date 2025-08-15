### Step 1: PHP + Composer
FROM php:8.2-fpm AS php-builder

WORKDIR /app

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy everything including artisan
COPY . .

# Install PHP dependencies without running scripts yet
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Run artisan scripts now that artisan exists
RUN php artisan key:generate
RUN php artisan package:discover

### Step 2: Node.js for frontend (Vite)
FROM node:18 AS node-builder

WORKDIR /app

# Copy app from PHP builder stage
COPY --from=php-builder /app /app

# Install Node dependencies & build
RUN npm install
RUN npm run build

### Step 3: Final runtime container
FROM php:8.2-fpm

WORKDIR /var/www

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libxml2-dev libzip-dev libpng-dev libjpeg-dev libonig-dev \
    sqlite3 libsqlite3-dev \
 && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app + vendor + built frontend
COPY --from=php-builder /app /var/www
COPY --from=node-builder /app/public/build /var/www/public/build

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
