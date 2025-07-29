#!/bin/bash

# Citadel Docker Deployment Script
# This script handles Laravel application deployment inside Docker containers

set -e

# Configuration for Docker environment
APP_PATH="/var/www/html"
LOG_FILE="/tmp/citadel-deploy.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}" | tee -a $LOG_FILE
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ERROR: $1${NC}" | tee -a $LOG_FILE
    exit 1
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] WARNING: $1${NC}" | tee -a $LOG_FILE
}

log "Starting Citadel Docker deployment process..."

# Check if application directory exists
if [ ! -d "$APP_PATH" ]; then
    error "Application directory $APP_PATH does not exist"
fi

cd $APP_PATH

# Function to put application in maintenance mode
maintenance_on() {
    log "Putting application in maintenance mode..."
    php artisan down --render='errors::503' --secret="citadel-deploy-$(date +%s)" || true
}

# Function to bring application out of maintenance mode
maintenance_off() {
    log "Bringing application out of maintenance mode..."
    php artisan up || true
}

# Function to install/update dependencies
install_dependencies() {
    log "Installing/updating PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
    
    if [ -f "package.json" ]; then
        log "Installing/updating Node.js dependencies..."
        npm ci --production || npm install --production
        
        log "Building frontend assets..."
        npm run build || npm run production
    fi
}

# Function to run database migrations
run_migrations() {
    log "Running database migrations..."
    php artisan migrate --force
}

# Function to clear and cache configuration
optimize_application() {
    log "Optimizing application..."
    
    # Clear all caches
    php artisan config:clear || true
    php artisan route:clear || true
    php artisan view:clear || true
    php artisan cache:clear || true
    
    # Cache configuration for production
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
    php artisan optimize || true
}

# Function to restart queue workers (Docker-safe)
restart_queues() {
    log "Restarting queue workers..."
    php artisan queue:restart || true
    
    # Signal supervisor to restart workers if available
    if [ -f "/var/run/supervisor.sock" ]; then
        supervisorctl -s unix:///var/run/supervisor.sock restart citadel-worker:* || true
    fi
}

# Function to setup Passport keys (Docker-safe)
setup_passport() {
    log "Setting up Passport keys..."
    if [ ! -f "storage/oauth-private.key" ] || [ ! -f "storage/oauth-public.key" ]; then
        php artisan passport:keys --force || true
        log "Passport keys generated"
    else
        log "Passport keys already exist, skipping generation"
    fi
}

# Function to set proper permissions (Docker-safe, no sudo)
set_permissions() {
    log "Setting proper file permissions..."
    
    # Set directory permissions (no ownership change needed in Docker)
    find $APP_PATH -type d -exec chmod 755 {} \; 2>/dev/null || true
    
    # Set file permissions
    find $APP_PATH -type f -exec chmod 644 {} \; 2>/dev/null || true
    
    # Set executable permissions for artisan
    chmod +x $APP_PATH/artisan 2>/dev/null || true
    
    # Set writable permissions for storage and cache
    chmod -R 775 $APP_PATH/storage 2>/dev/null || true
    chmod -R 775 $APP_PATH/bootstrap/cache 2>/dev/null || true
    
    log "File permissions set (Docker environment)"
}

# Function to run health checks (Docker-safe)
health_check() {
    log "Running health checks..."
    
    # Check if application is responding (using localhost)
    if command -v curl &> /dev/null; then
        HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health || echo "000")
        if [ "$HTTP_STATUS" = "200" ]; then
            log "✅ Application health check passed"
        else
            warning "⚠️  Application health check failed (HTTP $HTTP_STATUS)"
        fi
    fi
    
    # Check database connection
    if php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database connection successful'; } catch (Exception \$e) { echo 'Database connection failed: ' . \$e->getMessage(); }" 2>/dev/null | grep -q "successful"; then
        log "✅ Database connection check passed"
    else
        warning "⚠️  Database connection check failed"
    fi
    
    # Check storage permissions
    if [ -w "$APP_PATH/storage/logs" ]; then
        log "✅ Storage permissions check passed"
    else
        warning "⚠️  Storage permissions check failed"
    fi
}

# Function to seed essential data
seed_essential_data() {
    log "Seeding essential data..."
    php artisan db:seed --class=RolesAndPermissionsSeeder --force || true
    log "Essential data seeded"
}

# Main deployment function for Docker
deploy() {
    local start_time=$(date +%s)
    
    trap 'error "Docker deployment failed! Check logs at $LOG_FILE"' ERR
    
    # Put application in maintenance mode
    maintenance_on
    
    # Install dependencies
    install_dependencies
    
    # Run database migrations
    run_migrations
    
    # Seed essential data
    seed_essential_data
    
    # Setup Passport
    setup_passport
    
    # Optimize application
    optimize_application
    
    # Restart queue workers
    restart_queues
    
    # Set proper permissions
    set_permissions
    
    # Bring application out of maintenance mode
    maintenance_off
    
    # Run health checks
    health_check
    
    local end_time=$(date +%s)
    local duration=$((end_time - start_time))
    
    log "✅ Docker deployment completed successfully in ${duration} seconds!"
}

# Quick setup function for fresh containers
quick_setup() {
    log "Starting quick Docker setup..."
    
    # Generate application key if not set
    if ! grep -q "APP_KEY=" .env 2>/dev/null || [ -z "$(grep "APP_KEY=" .env | cut -d'=' -f2)" ]; then
        log "Generating application key..."
        php artisan key:generate --force
    fi
    
    # Install dependencies
    install_dependencies
    
    # Run migrations and seed
    run_migrations
    seed_essential_data
    
    # Setup Passport
    setup_passport
    
    # Optimize application
    optimize_application
    
    # Set permissions
    set_permissions
    
    log "✅ Quick Docker setup completed!"
}

# Health check only
health_only() {
    log "Running Docker health checks..."
    health_check
}

# Script usage
usage() {
    echo "Usage: $0 {deploy|setup|health}"
    echo ""
    echo "Commands:"
    echo "  deploy - Full deployment process"
    echo "  setup  - Quick setup for fresh containers" 
    echo "  health - Run health checks only"
    echo ""
    echo "This script is optimized for Docker environments and does not require sudo privileges."
    exit 1
}

# Main script logic
case "${1:-}" in
    deploy)
        deploy
        ;;
    setup)
        quick_setup
        ;;
    health)
        health_only
        ;;
    *)
        usage
        ;;
esac
