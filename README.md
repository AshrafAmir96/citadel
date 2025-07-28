# Citadel - Laravel Backend Boilerplate

<p align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## 🚀 About Citadel

Citadel is a comprehensive Laravel backend boilerplate designed to jumpstart your web application development. Built with Laravel 12 and modern PHP 8.2+, it provides a solid foundation with pre-configured authentication, permissions, media handling, and search capabilities.

## ✨ Features

- **🔐 Authentication & Authorization**
  - Laravel Passport OAuth2 server implementation
  - Role-based permissions with Spatie Laravel Permission
  - JWT token authentication for APIs

- **📁 Media Management**
  - File uploads and media library with Spatie Laravel Medialibrary
  - Image processing and optimization
  - Multiple storage driver support

- **🔍 Search Capabilities**
  - Full-text search with Laravel Scout
  - Configurable search drivers
  - Indexing and querying optimization

- **🧪 Testing Suite**
  - Pest PHP testing framework
  - Feature and unit test examples
  - CI/CD ready test configuration

- **🎨 Frontend Ready**
  - Tailwind CSS 4.0 integration
  - Vite build system
  - Modern JavaScript with Axios

- **⚡ Development Tools**
  - Laravel Pint for code styling
  - Laravel Pail for log monitoring
  - Laravel Sail for Docker development
  - Concurrent development server setup

## 📦 Key Packages

### Backend Dependencies

| Package | Version | Description |
|---------|---------|-------------|
| `laravel/framework` | ^12.0 | Core Laravel framework |
| `laravel/passport` | ^13.0 | OAuth2 server implementation |
| `laravel/scout` | ^10.17 | Full-text search |
| `spatie/laravel-medialibrary` | ^11.13 | Media file management |
| `spatie/laravel-permission` | ^6.21 | Role and permission management |
| `laravel/tinker` | ^2.10.1 | Interactive PHP REPL |

### Development Dependencies

| Package | Version | Description |
|---------|---------|-------------|
| `pestphp/pest` | ^3.8 | Modern PHP testing framework |
| `laravel/pint` | ^1.13 | PHP code style fixer |
| `laravel/sail` | ^1.41 | Docker development environment |
| `laravel/pail` | ^1.2.2 | Log monitoring tool |

### Frontend Dependencies

| Package | Version | Description |
|---------|---------|-------------|
| `tailwindcss` | ^4.0.0 | Utility-first CSS framework |
| `vite` | ^7.0.4 | Next-generation frontend tooling |
| `axios` | ^1.8.2 | HTTP client library |

## 🛠 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer 2.0+
- Node.js 18+ and npm
- SQLite/MySQL/PostgreSQL database

### Quick Start

1. **Clone the repository**
   ```bash
   git clone <repository-url> citadel
   cd citadel
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   touch database/database.sqlite  # For SQLite
   php artisan migrate
   ```

6. **Passport setup**
   ```bash
   php artisan passport:install
   ```

7. **Start development servers**
   ```bash
   composer run dev
   ```

This will start the Laravel server, queue worker, and Vite development server concurrently.

## 🗄 Database Structure

The boilerplate includes the following database tables:

- **users** - User authentication and profile data
- **password_reset_tokens** - Password reset functionality
- **sessions** - User session management
- **oauth_*** - Passport OAuth2 tables for API authentication
- **cache** - Application caching
- **jobs** - Queue system tables

## 🔧 Configuration

### Environment Variables

Key environment variables to configure:

```env
APP_NAME=Citadel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

SCOUT_DRIVER=database
```

### Passport Configuration

After installation, configure Passport in your `AuthServiceProvider`:

```php
use Laravel\Passport\Passport;

public function boot()
{
    Passport::loadKeysFrom(storage_path());
    // Additional Passport configuration
}
```

## 📚 API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `POST /api/auth/register` - User registration
- `POST /api/auth/logout` - User logout
- `GET /api/user` - Get authenticated user (requires auth:api middleware)

## 🧪 Testing

Run the test suite using Pest:

```bash
# Run all tests
composer test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

## 🚀 Deployment

### Production Setup

1. **Environment configuration**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Asset compilation**
   ```bash
   npm run build
   ```

3. **Optimization**
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

## 📖 Development Commands

### Custom Composer Scripts

```bash
# Start development environment (server + queue + vite)
composer run dev

# Run tests with configuration clearing
composer run test
```

### Artisan Commands

```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Create Passport keys
php artisan passport:install

# Clear application cache
php artisan cache:clear

# Monitor logs in real-time
php artisan pail
```

## 🐳 Docker Development

### Quick Start with Docker

1. **Clone and start the development environment**
   ```bash
   git clone <repository-url> citadel
   cd citadel
   docker-compose up -d
   ```

2. **Access the application**
   - Application: http://localhost:8000
   - Database Admin: http://localhost:8080 (phpMyAdmin)
   - Mail Testing: http://localhost:8025 (MailHog)
   - Redis Admin: http://localhost:8081 (Redis Commander)

### Docker Services

| Service | Port | Description |
|---------|------|-------------|
| `app` | 8000 | Laravel application |
| `mysql` | 3306 | MySQL database |
| `redis` | 6379 | Redis cache & sessions |
| `meilisearch` | 7700 | Search engine |
| `mailhog` | 1025/8025 | Email testing |
| `phpmyadmin` | 8080 | Database management |

### Docker Commands

```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f app

# Run artisan commands
docker-compose exec app php artisan migrate

# Run tests
docker-compose exec app php artisan test

# Access application shell
docker-compose exec app sh

# Stop all services
docker-compose down
```

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

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Workflow

1. **Local Development**
   ```bash
   # Using Laravel Sail
   ./vendor/bin/sail up -d
   
   # Or using Docker Compose
   docker-compose up -d
   
   # Or traditional setup
   composer run dev
   ```

2. **Running Tests**
   ```bash
   # Run all tests
   php artisan test
   
   # Run with coverage
   php artisan test --coverage
   
   # Run specific test
   php artisan test --filter=ExampleTest
   ```

3. **Code Quality**
   ```bash
   # Fix code style
   composer run pint
   
   # Run static analysis
   ./vendor/bin/phpstan analyse
   ```

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com) - The web artisans framework
- [Spatie](https://spatie.be) - Amazing Laravel packages
- [Pest PHP](https://pestphp.com) - Modern testing framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
