#!/bin/bash

# Citadel Deployment Script
# This script handles Laravel application deployment on the server

set -e

# Configuration
APP_PATH="/var/www/citadel"
APP_USER="www-data"
BACKUP_PATH="/var/backups/citadel"
LOG_FILE="/var/log/citadel-deploy.log"

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

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   error "This script should not be run as root for security reasons"
fi

# Create necessary directories
mkdir -p $BACKUP_PATH
mkdir -p $(dirname $LOG_FILE)

log "Starting Citadel deployment process..."

# Check if application directory exists
if [ ! -d "$APP_PATH" ]; then
    error "Application directory $APP_PATH does not exist"
fi

cd $APP_PATH

# Function to create backup
create_backup() {
    log "Creating backup..."
    BACKUP_NAME="citadel-backup-$(date +%Y%m%d_%H%M%S).tar.gz"
    tar -czf "$BACKUP_PATH/$BACKUP_NAME" \
        --exclude='vendor' \
        --exclude='node_modules' \
        --exclude='storage/logs/*' \
        --exclude='storage/framework/cache/*' \
        --exclude='storage/framework/sessions/*' \
        --exclude='storage/framework/views/*' \
        .
    
    log "Backup created: $BACKUP_PATH/$BACKUP_NAME"
    
    # Keep only last 5 backups
    cd $BACKUP_PATH
    ls -t citadel-backup-*.tar.gz | tail -n +6 | xargs -r rm --
    log "Old backups cleaned up"
}

# Function to put application in maintenance mode
maintenance_on() {
    log "Putting application in maintenance mode..."
    php artisan down --render='errors::503' --secret="citadel-deploy-$(date +%s)"
}

# Function to bring application out of maintenance mode
maintenance_off() {
    log "Bringing application out of maintenance mode..."
    php artisan up
}

# Function to install/update dependencies
install_dependencies() {
    log "Installing/updating PHP dependencies..."
    composer install --no-dev --optimize-autoloader --no-interaction
    
    if [ -f "package.json" ]; then
        log "Installing/updating Node.js dependencies..."
        npm ci --production
        
        log "Building frontend assets..."
        npm run build
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
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    
    # Cache configuration for production
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan optimize
}

# Function to restart queue workers
restart_queues() {
    log "Restarting queue workers..."
    php artisan queue:restart
    
    # If using Supervisor, restart workers
    if command -v supervisorctl &> /dev/null; then
        sudo supervisorctl restart citadel-worker:*
    fi
}

# Function to setup Passport keys
setup_passport() {
    log "Setting up Passport keys..."
    if [ ! -f "storage/oauth-private.key" ] || [ ! -f "storage/oauth-public.key" ]; then
        php artisan passport:keys --force
        log "Passport keys generated"
    else
        log "Passport keys already exist, skipping generation"
    fi
}

# Function to set proper permissions
set_permissions() {
    log "Setting proper file permissions..."
    
    # Set ownership
    sudo chown -R $APP_USER:$APP_USER $APP_PATH
    
    # Set directory permissions
    find $APP_PATH -type d -exec chmod 755 {} \;
    
    # Set file permissions
    find $APP_PATH -type f -exec chmod 644 {} \;
    
    # Set executable permissions for artisan
    chmod +x $APP_PATH/artisan
    
    # Set writable permissions for storage and cache
    chmod -R 775 $APP_PATH/storage
    chmod -R 775 $APP_PATH/bootstrap/cache
}

# Function to run health checks
health_check() {
    log "Running health checks..."
    
    # Check if application is responding
    if command -v curl &> /dev/null; then
        HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health || echo "000")
        if [ "$HTTP_STATUS" = "200" ]; then
            log "✅ Application health check passed"
        else
            warning "⚠️  Application health check failed (HTTP $HTTP_STATUS)"
        fi
    fi
    
    # Check database connection
    if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" &> /dev/null; then
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

# Function to send deployment notification
send_notification() {
    local status=$1
    local message=$2
    
    # Example: Send to Slack (replace with your webhook URL)
    if [ ! -z "$SLACK_WEBHOOK_URL" ]; then
        curl -X POST -H 'Content-type: application/json' \
            --data "{\"text\":\"🚀 Citadel Deployment $status: $message\"}" \
            $SLACK_WEBHOOK_URL &> /dev/null || true
    fi
    
    # Example: Send email notification
    if command -v mail &> /dev/null && [ ! -z "$NOTIFICATION_EMAIL" ]; then
        echo "$message" | mail -s "Citadel Deployment $status" $NOTIFICATION_EMAIL || true
    fi
}

# Main deployment function
deploy() {
    local start_time=$(date +%s)
    
    trap 'error "Deployment failed! Check logs at $LOG_FILE"' ERR
    
    # Create backup
    create_backup
    
    # Put application in maintenance mode
    maintenance_on
    
    # Install dependencies
    install_dependencies
    
    # Run database migrations
    run_migrations
    
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
    
    log "✅ Deployment completed successfully in ${duration} seconds!"
    send_notification "SUCCESS" "Deployment completed in ${duration} seconds"
}

# Rollback function
rollback() {
    log "Starting rollback process..."
    
    # Find the latest backup
    LATEST_BACKUP=$(ls -t $BACKUP_PATH/citadel-backup-*.tar.gz 2>/dev/null | head -n1)
    
    if [ -z "$LATEST_BACKUP" ]; then
        error "No backup found for rollback"
    fi
    
    log "Rolling back to: $LATEST_BACKUP"
    
    # Put application in maintenance mode
    maintenance_on
    
    # Extract backup
    tar -xzf "$LATEST_BACKUP" -C "$APP_PATH"
    
    # Set permissions
    set_permissions
    
    # Bring application out of maintenance mode
    maintenance_off
    
    log "✅ Rollback completed successfully!"
    send_notification "ROLLBACK" "Application rolled back to previous version"
}

# Script usage
usage() {
    echo "Usage: $0 {deploy|rollback|health|backup}"
    echo ""
    echo "Commands:"
    echo "  deploy   - Deploy the application"
    echo "  rollback - Rollback to the previous version"
    echo "  health   - Run health checks"
    echo "  backup   - Create a backup"
    echo ""
    echo "Environment variables:"
    echo "  SLACK_WEBHOOK_URL    - Slack webhook for notifications"
    echo "  NOTIFICATION_EMAIL   - Email address for notifications"
    exit 1
}

# Main script logic
case "${1:-}" in
    deploy)
        deploy
        ;;
    rollback)
        rollback
        ;;
    health)
        health_check
        ;;
    backup)
        create_backup
        ;;
    *)
        usage
        ;;
esac
