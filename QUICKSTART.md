# 🚀 Citadel Quick Setup Guide

Get your Citadel Laravel backend running in under 5 minutes!

## Prerequisites

- **Docker & Docker Compose** (Recommended) OR
- **PHP 8.2+**, **Composer**, **Node.js**, **MySQL/PostgreSQL**

## Option 1: Docker Setup (Recommended) ⭐

### Step 1: Clone and Start
```bash
git clone <repository-url> citadel
cd citadel
docker-compose up -d
```

### Step 2: Wait for Services
```bash
# Monitor startup (wait for "Laravel application setup complete!")
docker-compose logs -f app
```

### Step 3: Create Super Admin
```bash
docker-compose exec app php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"
```

**🎉 Done! Access your API at http://localhost:8000**

### Quick Test
```bash
# Test API endpoint
curl http://localhost:8000/api/health

# View API documentation  
open http://localhost:8000/docs/api
```

## Option 2: Traditional Setup

### Step 1: Install Dependencies
```bash
git clone <repository-url> citadel
cd citadel
composer install
npm install
```

### Step 2: Environment Setup
```bash
cp .env.example .env
php artisan key:generate

# Configure your database in .env
php artisan migrate --seed
```

### Step 3: OAuth Setup
```bash
php artisan passport:keys
php artisan passport:client --personal
```

### Step 4: Create Super Admin
```bash
php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"
```

### Step 5: Start Development Server
```bash
npm run dev &
php artisan serve
```

**🎉 Done! Access your API at http://localhost:8000**

## Option 3: Laravel Sail

### Step 1: Setup Sail
```bash
git clone <repository-url> citadel
cd citadel
./vendor/bin/sail up -d
```

### Step 2: Create Super Admin
```bash
./vendor/bin/sail artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"
```

**🎉 Done! Access your API at http://localhost**

## 🔧 Post-Setup Steps

### 1. Test Your API
```bash
# Health check
curl http://localhost:8000/api/health

# Get access token (replace with your credentials)
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@yourcompany.com","password":"SecurePassword123!"}'
```

### 2. Explore the Features
- **📚 API Documentation**: http://localhost:8000/docs/api
- **📧 Email Testing**: http://localhost:8025 (MailHog)
- **🔍 Search Dashboard**: http://localhost:7700 (Meilisearch)
- **🗄️ Database Admin**: http://localhost:8080 (phpMyAdmin)

### 3. Development Commands
```bash
# Run tests
docker-compose exec app php artisan test
# OR: ./vendor/bin/pest

# Generate API docs
docker-compose exec app php artisan scramble:generate

# Clear caches
docker-compose exec app php artisan optimize:clear

# View logs
docker-compose logs -f app
```

## 🛠️ Troubleshooting

### Port Conflicts
If ports are already in use, update them in `docker-compose.yml`:
```yaml
services:
  app:
    ports:
      - "8001:80"  # Changed from 8000 to 8001
```

### Permission Issues (Linux/macOS)
```bash
sudo chown -R $USER:$USER .
chmod -R 755 storage bootstrap/cache
```

### Database Connection Issues
```bash
# Check if database is ready
docker-compose exec mysql mysql -u citadel -psecret -e "SELECT 1"

# Reset database
docker-compose exec app php artisan migrate:fresh --seed
```

### Super Admin Creation Issues
```bash
# Check if roles exist
docker-compose exec app php artisan db:seed --class=RolesAndPermissionsSeeder

# Verify user was created
docker-compose exec app php artisan tinker --execute="App\Models\User::count()"
```

## 📚 Next Steps

1. **Read the Documentation**: Check out `README.md` for detailed features
2. **Customize Configuration**: Edit `config/citadel.php` for your needs
3. **Add Your Business Logic**: Start building your controllers in `app/Http/Controllers/Api/`
4. **Set Up CI/CD**: Use the included GitLab CI pipeline
5. **Deploy to Production**: Follow the deployment guide in `DEPLOYMENT.md`

## 🆘 Getting Help

- **Documentation**: Check the root folder for detailed guides
- **Issues**: Open an issue on GitHub
- **Discussions**: Join the community discussions
- **Email**: Contact support@owlfice.com

**Happy coding with Citadel! 🏰**
