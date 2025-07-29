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
php artisan migrate:fresh --force

# Run only the roles and permissions seeder (not the full DatabaseSeeder)
php artisan db:seed --class=RolesAndPermissionsSeeder --force

# Skip Passport keys generation for now (can be done manually later)
# php artisan passport:keys --force

# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel application setup complete!"

# Create supervisor log directory with proper permissions
mkdir -p /tmp/supervisor

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
