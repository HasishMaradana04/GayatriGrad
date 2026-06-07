# =============================================================================
# Dockerfile — Alumni Association (Laravel 11 + Filament 3)
# Production-ready image for Render deployment
# =============================================================================
#
# Base: php:8.2-apache (matches composer.json "php": "^8.2")
# Database: MySQL (via environment variables — nothing hardcoded)
# Frontend: Vite assets compiled via multi-stage build (Node 20)
# Secrets: APP_KEY and all credentials come from environment variables
#
# Build:  docker build -t alumni-app .
# Run:    docker run -p 80:80 --env-file .env alumni-app
# =============================================================================

# ---------------------------------------------------------------------------
# Stage 1: Build frontend assets
# ---------------------------------------------------------------------------
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---------------------------------------------------------------------------
# Stage 2: Final application image
# ---------------------------------------------------------------------------
FROM php:8.2-apache

# ---------------------------------------------------------------------------
# 1. Install system dependencies required by PHP extensions
# ---------------------------------------------------------------------------
# - libpng/libjpeg/libwebp/libfreetype: needed by GD (image processing for
#   Filament file uploads and any image manipulation)
# - libzip: needed by zip extension (Composer requires it)
# - libicu: needed by intl extension (Laravel locale support)
# - libonig: needed by mbstring (string handling)
# - unzip: needed by Composer to extract packages
# ---------------------------------------------------------------------------
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# 2. Install PHP extensions required by Laravel 11 + Filament + MySQL
# ---------------------------------------------------------------------------
# - pdo_mysql: MySQL database driver (DB_CONNECTION=mysql)
# - mbstring: multi-byte string support (Laravel requirement)
# - bcmath: arbitrary precision math (used by some Laravel features)
# - gd: image processing (Filament file uploads, image manipulation)
# - intl: internationalization (locale handling)
# - zip: archive support (Composer package extraction)
# - opcache: PHP bytecode cache (critical for production performance)
# - exif: EXIF data reading (image uploads in Filament)
# ---------------------------------------------------------------------------
RUN docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        gd \
        intl \
        zip \
        opcache \
        exif

# ---------------------------------------------------------------------------
# 3. Configure OPcache for production
# ---------------------------------------------------------------------------
# These settings optimize PHP performance in production by caching compiled
# bytecode in memory, eliminating the need to parse PHP files on each request.
# ---------------------------------------------------------------------------
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# ---------------------------------------------------------------------------
# 4. Enable Apache modules
# ---------------------------------------------------------------------------
# - rewrite: required for Laravel's .htaccess URL rewriting (pretty URLs)
# - deflate: used by existing .htaccess for gzip compression
# - expires: used by existing .htaccess for browser caching headers
# - headers: used by existing .htaccess for Cache-Control headers
# ---------------------------------------------------------------------------
RUN a2enmod rewrite deflate expires headers

# ---------------------------------------------------------------------------
# 5. Configure Apache virtual host
# ---------------------------------------------------------------------------
# Copies our custom vhost config that sets DocumentRoot to /var/www/html/public
# and enables AllowOverride All so Laravel's .htaccess works correctly.
# ---------------------------------------------------------------------------
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# ---------------------------------------------------------------------------
# 6. Set working directory and copy application code
# ---------------------------------------------------------------------------
WORKDIR /var/www/html
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1 (frontend-builder)
COPY --from=frontend-builder /app/public/build /var/www/html/public/build

# ---------------------------------------------------------------------------
# 7. Install Composer and PHP dependencies
# ---------------------------------------------------------------------------
# - Downloads Composer from the official image (multi-stage copy)
# - Runs composer install with production flags:
#   --no-dev: excludes dev dependencies (phpunit, faker, etc.)
#   --optimize-autoloader: generates optimized class map for performance
#   --no-interaction: prevents interactive prompts during build
# - Note: APP_KEY is NOT generated here — it must be provided via env vars
# ---------------------------------------------------------------------------
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-progress

# ---------------------------------------------------------------------------
# 8. Create storage directory structure and set permissions
# ---------------------------------------------------------------------------
# Laravel requires these directories to exist and be writable by the web
# server (www-data). They are in .gitignore so won't exist in a fresh clone.
# ---------------------------------------------------------------------------
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# ---------------------------------------------------------------------------
# 9. Copy and prepare the startup entrypoint script
# ---------------------------------------------------------------------------
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# ---------------------------------------------------------------------------
# 10. Expose port 80 (Apache default) and set the entrypoint
# ---------------------------------------------------------------------------
# Render automatically routes traffic to the exposed port.
# The entrypoint script handles runtime initialization (storage:link,
# config caching, optional migrations) before starting Apache.
# ---------------------------------------------------------------------------
EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
