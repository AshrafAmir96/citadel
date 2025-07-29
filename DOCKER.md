# Docker Development Guide

Docker provides a consistent development environment across all platforms.

## 🚀 Quick Start with Docker

```bash
# Clone and start the development environment
git clone <repository-url> citadel
cd citadel
docker-compose up -d

# Wait for services to be ready (30-60 seconds)
docker-compose logs -f app

# Create your first super admin user
docker-compose exec app php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Admin User"
```

**✅ Your Citadel application is now ready at http://localhost:8000**

## 🌐 Service Access Points

| Service | URL | Credentials | Purpose |
|---------|-----|-------------|---------|
| **Laravel App** | http://localhost:8000 | - | Main application |
| **phpMyAdmin** | http://localhost:8080 | `citadel/secret` | Database management |
| **MailHog** | http://localhost:8025 | - | Email testing |
| **Redis Commander** | http://localhost:8081 | - | Redis management |
| **Meilisearch** | http://localhost:7700 | `citadel_search_key` | Search dashboard |

## 🛠 Docker Services

| Service | Port | Container | Description |
|---------|------|-----------|-------------|
| `app` | 8000 | citadel-app | Laravel application (PHP 8.2-FPM) |
| `mysql` | 3306 | citadel-mysql | MySQL 8.0 database |
| `redis` | 6379 | citadel-redis | Redis 7 for cache & sessions |
| `meilisearch` | 7700 | citadel-meilisearch | Full-text search engine |
| `queue` | - | citadel-queue | Background job processing |
| `scheduler` | - | citadel-scheduler | Laravel task scheduling |
| `mailhog` | 1025/8025 | citadel-mailhog | SMTP testing server |

## ⚡ Essential Docker Commands

### 🏰 Application Management
```bash
# Create super admin user (interactive)
docker-compose exec app php artisan citadel:create-super-admin

# Create super admin user (direct)
docker-compose exec app php artisan citadel:create-super-admin \
  --email=admin@example.com \
  --password=SecurePassword123! \
  --name="Super Admin"

# Run database migrations and seeders
docker-compose exec app php artisan migrate:fresh --seed

# Generate API documentation
docker-compose exec app php artisan scramble:generate
```

### 🐳 Container Management
```bash
# Start all services in background
docker-compose up -d

# View real-time logs
docker-compose logs -f app

# Execute Laravel commands
docker-compose exec app php artisan migrate
docker-compose exec app php artisan test

# Access application shell
docker-compose exec app sh

# Rebuild containers after changes
docker-compose up -d --build

# Stop all services
docker-compose down

# Reset everything (⚠️ destroys data)
docker-compose down -v
docker system prune -a
```

## 🔧 Advanced Docker Usage

**Run with additional tools:**
```bash
# Include phpMyAdmin and Redis Commander
docker-compose --profile tools up -d

# Production-like setup with Nginx
docker-compose --profile production up -d
```

**Database operations:**
```bash
# Create database backup
docker-compose exec mysql mysqldump -u citadel -psecret citadel > backup.sql

# Restore database
docker-compose exec -T mysql mysql -u citadel -psecret citadel < backup.sql

# Access MySQL directly
docker-compose exec mysql mysql -u citadel -psecret citadel
```

**Performance optimization:**
```bash
# Use BuildKit for faster builds
DOCKER_BUILDKIT=1 docker-compose build

# Optimize containers for production
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up -d
```

## 🔧 Docker Troubleshooting

**Common Issues:**

1. **Frontend build fails with "vite: not found"**
   ```bash
   # Rebuild with no cache
   docker-compose build --no-cache
   
   # Or build specific service
   docker-compose build app
   ```

2. **SQLite extension compilation fails**
   ```bash
   # Error: Package 'sqlite3' not found
   # This is fixed by including sqlite-dev in the Dockerfile
   # If you see this error, rebuild with no cache:
   docker-compose build --no-cache app
   ```

3. **Permission errors in containers**
   ```bash
   # Fix file permissions
   sudo chown -R $(id -u):$(id -g) .
   
   # Rebuild containers
   docker-compose up -d --build
   ```

4. **Database connection issues**
   ```bash
   # Check if MySQL is ready
   docker-compose logs mysql
   
   # Restart services in order
   docker-compose restart mysql
   docker-compose restart app
   ```

5. **Port conflicts**
   ```bash
   # Check what's using the ports
   netstat -tulpn | grep :8000
   
   # Stop conflicting services or change ports in docker-compose.yml
   ```

6. **Container build failures**
   ```bash
   # Clean up Docker cache and rebuild
   docker system prune -a
   docker-compose build --no-cache
   
   # If still failing, check Docker resources
   docker system df
   ```
