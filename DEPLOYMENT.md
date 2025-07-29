# Deployment Guide

## 🚀 CI/CD Pipeline

The project includes a comprehensive GitLab CI/CD pipeline with the following stages:

### Pipeline Stages

1. **Test Stage**
   - Code style checking with Laravel Pint
   - Static analysis with PHPStan
   - Unit and feature tests with Pest PHP
   - Frontend asset building
   - Test coverage reporting

2. **Security Stage**
   - Composer dependency audit
   - NPM dependency audit
   - Static Application Security Testing (SAST)

3. **Build Stage**
   - Optimized production build
   - Asset compilation
   - Docker image creation

4. **Deploy Stage**
   - Staging deployment (automatic on `develop`)
   - Production deployment (manual on `main`)
   - Review apps for merge requests

### Required GitLab Variables

Set these variables in your GitLab project settings:

**Staging Environment:**
- `STAGING_SERVER` - Staging server hostname
- `STAGING_USER` - SSH username for staging
- `STAGING_SSH_PRIVATE_KEY` - SSH private key for staging
- `STAGING_PATH` - Application path on staging server
- `STAGING_URL` - Staging application URL

**Production Environment:**
- `PRODUCTION_SERVER` - Production server hostname  
- `PRODUCTION_USER` - SSH username for production
- `PRODUCTION_SSH_PRIVATE_KEY` - SSH private key for production
- `PRODUCTION_PATH` - Application path on production server
- `PRODUCTION_URL` - Production application URL

**Optional Notifications:**
- `SLACK_WEBHOOK_URL` - Slack webhook for deployment notifications
- `NOTIFICATION_EMAIL` - Email for deployment notifications

### Deployment Script

The project includes a deployment script (`scripts/deploy.sh`) that can be used independently:

```bash
# Deploy application
./scripts/deploy.sh deploy

# Rollback to previous version  
./scripts/deploy.sh rollback

# Run health checks
./scripts/deploy.sh health

# Create backup
./scripts/deploy.sh backup
```

## 🚀 Production Setup

### 1. Environment configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 2. Asset compilation
```bash
npm run build
```

### 3. Optimization
```bash
composer install --optimize-autoloader --no-dev
php artisan optimize
```

## 🔒 Security Features

- **CSRF Protection** - Built-in CSRF token validation
- **SQL Injection Prevention** - Eloquent ORM with parameter binding
- **XSS Protection** - Blade template engine with automatic escaping
- **OAuth2 Security** - Passport implementation with secure token handling
- **Role-based Access Control** - Spatie Permission package integration
