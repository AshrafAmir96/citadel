# Citadel Laravel Backend Boilerplate
# Multi-stage Docker build for production deployment

# Build stage for frontend assets
FROM node:18-alpine AS frontend-builder

WORKDIR /app

# Copy package files first for better caching
COPY package*.json ./

# Install all dependencies (including devDependencies for build tools)
RUN npm ci

# Copy all necessary files for the build
COPY resources/ resources/
COPY public/ public/
COPY vite.config.js .
COPY tailwind.config.js* ./
COPY postcss.config.js* ./

# Build frontend assets
RUN npm run build

# Production stage
FROM php:8.2-fpm-alpine AS production

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    postgresql-dev \
    icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        pdo_sqlite \
        mbstring \
        zip \
        exif \
        pcntl \
        gd \
        intl \
        bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create application user
RUN addgroup -g 1001 -S appgroup && \
    adduser -u 1001 -S appuser -G appgroup

# Copy application files
COPY --chown=appuser:appgroup . .

# Copy built frontend assets from build stage
COPY --from=frontend-builder --chown=appuser:appgroup /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set up Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan optimize

# Set permissions
RUN chown -R appuser:appgroup /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/storage && \
    chmod -R 775 /var/www/html/bootstrap/cache

# Copy configuration files
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh

# Make entrypoint executable
RUN chmod +x /entrypoint.sh

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Switch to non-root user
USER appuser

# Set entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Development stage
FROM production AS development

# Switch back to root for development tools
USER root

# Install development dependencies
RUN apk add --no-cache git bash vim nano

# Install Xdebug for development
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Copy development php.ini
COPY docker/php-dev.ini /usr/local/etc/php/conf.d/99-development.ini

# Install development Composer dependencies
RUN composer install --optimize-autoloader --no-interaction

# Switch back to app user
USER appuser

# Development command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
