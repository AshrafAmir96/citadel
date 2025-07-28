#!/bin/sh

set -e

# Wait for database to be ready
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "Waiting for MySQL..."
    while ! nc -z $DB_HOST $DB_PORT; do
        sleep 1
    done
    echo "MySQL is ready!"
fi

# Run Laravel setup commands
echo "Setting up Laravel application..."

# Generate application key if not exists
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Run database migrations
php artisan migrate --force

# Generate Passport keys
php artisan passport:keys --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chown -R appuser:appgroup /var/www/html
chmod -R 755 /var/www/html
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "Laravel application setup complete!"

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
