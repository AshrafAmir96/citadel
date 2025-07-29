# 🚀 Citadel Deployment Guide

This guide covers deploying Citadel to production environments with proper security and performance configurations.

## 📋 Pre-Deployment Checklist

### ✅ Environment Setup
- [ ] Production server with PHP 8.2+, Composer, and web server
- [ ] Database server (MySQL 8.0+ or PostgreSQL 13+)
- [ ] Redis server for caching and sessions
- [ ] SSL certificate configured
- [ ] Domain name configured
- [ ] Environment variables secured

### ✅ Security Configuration
- [ ] `.env` file properly configured with production values
- [ ] Database credentials secured
- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production` set
- [ ] Strong `APP_KEY` generated
- [ ] OAuth keys generated and secured

## 🏗️ Deployment Methods

### Option 1: Traditional Server Deployment

#### Step 1: Server Preparation
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y php8.2-fpm php8.2-mysql php8.2-redis php8.2-mbstring \
  php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-gd \
  nginx mysql-server redis-server composer git

# Configure PHP-FPM and Nginx (configuration files provided separately)
```

#### Step 2: Application Deployment
```bash
# Clone repository
git clone <repository-url> /var/www/citadel
cd /var/www/citadel

# Install dependencies (production)
composer install --no-dev --optimize-autoloader
npm ci --production

# Set permissions
sudo chown -R www-data:www-data /var/www/citadel
sudo chmod -R 755 /var/www/citadel
sudo chmod -R 775 storage bootstrap/cache

# Environment setup
cp .env.example .env
# Edit .env with production values
php artisan key:generate

# Database setup
php artisan migrate --force
php artisan db:seed --class=RolesAndPermissionsSeeder --force

# OAuth setup
php artisan passport:keys --force
php artisan passport:client --personal --name="Citadel Personal Access Client"

# Create super admin
php artisan citadel:create-super-admin \
  --email="${ADMIN_EMAIL}" \
  --password="${ADMIN_PASSWORD}" \
  --name="System Administrator"

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

### Option 2: Docker Production Deployment

#### Step 1: Docker Compose for Production
Create `docker-compose.prod.yml`:
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      target: production
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    volumes:
      - ./storage:/var/www/html/storage
      - ./bootstrap/cache:/var/www/html/bootstrap/cache
    networks:
      - citadel-network
    depends_on:
      - mysql
      - redis

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/production.conf:/etc/nginx/conf.d/default.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - app
    networks:
      - citadel-network

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: citadel
      MYSQL_USER: citadel
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
    networks:
      - citadel-network

  redis:
    image: redis:7-alpine
    volumes:
      - redis_data:/data
    networks:
      - citadel-network

volumes:
  mysql_data:
  redis_data:

networks:
  citadel-network:
    driver: bridge
```

#### Step 2: Production Deployment
```bash
# Build and start production containers
docker-compose -f docker-compose.prod.yml up -d --build

# Wait for services to be ready
docker-compose -f docker-compose.prod.yml logs -f app

# Create super admin
docker-compose -f docker-compose.prod.yml exec app php artisan citadel:create-super-admin \
  --email="${ADMIN_EMAIL}" \
  --password="${ADMIN_PASSWORD}" \
  --name="System Administrator"

# Verify deployment
curl https://yourdomain.com/api/health
```

### Option 3: Cloud Platform Deployment (AWS/DigitalOcean/etc.)

#### AWS Elastic Beanstalk
```bash
# Install EB CLI
pip install awsebcli

# Initialize and deploy
eb init citadel
eb create production
eb deploy

# Set environment variables
eb setenv APP_ENV=production APP_DEBUG=false DB_HOST=your-rds-endpoint

# Create super admin via SSH
eb ssh
php artisan citadel:create-super-admin \
  --email="${ADMIN_EMAIL}" \
  --password="${ADMIN_PASSWORD}" \
  --name="System Administrator"
```

## 🔧 Production Configuration

### Environment Variables (.env)
```bash
# Application
APP_NAME="Citadel"
APP_ENV=production
APP_KEY=base64:your-32-character-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=citadel
DB_USERNAME=citadel
DB_PASSWORD=your-secure-password

# Cache & Sessions
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-redis-password
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls

# Search
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://your-meilisearch-host:7700
MEILISEARCH_KEY=your-meilisearch-key

# Citadel Configuration
CITADEL_SUPER_ADMIN_ROLE="Super Admin"
CITADEL_DEFAULT_USER_ROLE="User"
```

### Web Server Configuration

#### Nginx Configuration (`/etc/nginx/sites-available/citadel`)
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/citadel/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /etc/ssl/certs/yourdomain.com.crt;
    ssl_certificate_key /etc/ssl/private/yourdomain.com.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # File Upload Limits
    client_max_body_size 100M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🚀 Post-Deployment Tasks

### 1. Super Admin Setup
```bash
# Create your first super admin user
php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=VerySecurePassword123! \
  --name="System Administrator"

# Verify super admin was created
php artisan tinker --execute="App\Models\User::with('roles')->where('email', 'admin@yourcompany.com')->first()"
```

### 2. OAuth Client Setup
```bash
# Create personal access client
php artisan passport:client --personal --name="Citadel Personal Access Client"

# Create password grant client (for frontend apps)
php artisan passport:client --password --name="Citadel Web Client"
```

### 3. Performance Optimization
```bash
# Cache configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer dump-autoload --optimize

# Link storage directory
php artisan storage:link
```

### 4. Health Checks
```bash
# Test API endpoints
curl https://yourdomain.com/api/health

# Test authentication
curl -X POST https://yourdomain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@yourcompany.com","password":"VerySecurePassword123!"}'

# Test database connection
php artisan tinker --execute="DB::connection()->getPdo()"
```

## 🔐 Security Best Practices

### 1. Environment Security
- Store sensitive configuration in environment variables
- Use strong, unique passwords for all services
- Enable two-factor authentication where possible
- Regularly rotate API keys and passwords

### 2. Server Hardening
```bash
# Disable root SSH access
sudo sed -i 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config

# Configure firewall
sudo ufw allow ssh
sudo ufw allow http
sudo ufw allow https
sudo ufw enable

# Set up fail2ban
sudo apt install fail2ban
```

### 3. Database Security
```sql
-- Create dedicated database user
CREATE USER 'citadel'@'localhost' IDENTIFIED BY 'secure_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON citadel.* TO 'citadel'@'localhost';
FLUSH PRIVILEGES;
```

### 4. SSL/TLS Configuration
```bash
# Generate SSL certificate with Let's Encrypt
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

## 📊 Monitoring & Maintenance

### 1. Log Monitoring
```bash
# Monitor application logs
tail -f storage/logs/laravel.log

# Monitor web server logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# Set up log rotation
sudo logrotate -d /etc/logrotate.conf
```

### 2. Database Backup
```bash
# Create automated backup script
#!/bin/bash
BACKUP_DIR="/var/backups/citadel"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u citadel -p citadel > $BACKUP_DIR/citadel_$DATE.sql
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz /var/www/citadel/storage

# Keep only last 7 days of backups
find $BACKUP_DIR -type f -mtime +7 -delete
```

### 3. Health Monitoring
Set up monitoring for:
- Application uptime and response time
- Database connection and performance
- Redis connection and memory usage
- Disk space and server resources
- SSL certificate expiration

## 🔄 Deployment Updates

### Zero-Downtime Deployment
```bash
#!/bin/bash
# deployment-script.sh

# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader
npm ci --production

# Run database migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

echo "Deployment completed successfully!"
```

### Rollback Strategy
```bash
#!/bin/bash
# rollback-script.sh

# Checkout previous version
git checkout HEAD~1

# Restore dependencies
composer install --no-dev --optimize-autoloader

# Rollback database if needed
php artisan migrate:rollback --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

echo "Rollback completed!"
```

## 🆘 Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/citadel
   sudo chmod -R 775 storage bootstrap/cache
   ```

2. **Database Connection Issues**
   ```bash
   php artisan config:clear
   php artisan migrate:status
   ```

3. **OAuth Key Issues**
   ```bash
   php artisan passport:keys --force
   php artisan config:cache
   ```

4. **Cache Issues**
   ```bash
   php artisan optimize:clear
   sudo systemctl restart php8.2-fpm
   ```

## 📞 Support

For deployment support:
- **Documentation**: Check the `docs/` folder
- **Issues**: Open a GitHub issue
- **Professional Support**: Contact support@owlfice.com

**Successful deployment! Your Citadel application is now live! 🎉**
