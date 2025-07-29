# Installation Guide

This guide provides detailed instructions for setting up Citadel in different environments.

## 📋 Prerequisites

Ensure you have the following installed on your system:

| Requirement | Version | Download |
|-------------|---------|----------|
| **PHP** | 8.2+ | [php.net](https://www.php.net/downloads) |
| **Composer** | 2.0+ | [getcomposer.org](https://getcomposer.org) |
| **Node.js** | 18+ | [nodejs.org](https://nodejs.org) |
| **Database** | MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8+ | - |

## 🚀 Step-by-Step Installation

### 1. Clone the Repository
```bash
git clone <repository-url> citadel
cd citadel
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies  
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

**For SQLite (Development):**
```bash
touch database/database.sqlite
php artisan migrate
```

**For MySQL/PostgreSQL:**
```bash
# Update .env with your database credentials
php artisan migrate
```

### 5. OAuth2 Setup
```bash
# Generate Passport keys
php artisan passport:install

# Optional: Create personal access client
php artisan passport:client --personal
```

### 6. Asset Compilation
```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start Development Server
```bash
# Option 1: Use the custom dev script (Recommended)
composer run dev

# Option 2: Individual commands
php artisan serve &
php artisan queue:work &
npm run dev
```

## 🔧 Post-Installation Setup

### Create Admin User
```bash
php artisan tinker
```
```php
$user = App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'email_verified_at' => now(),
]);
```

### Setup Permissions (Optional)
```bash
php artisan tinker
```
```php
// Create roles and permissions
$role = Spatie\Permission\Models\Role::create(['name' => 'admin']);
$permission = Spatie\Permission\Models\Permission::create(['name' => 'manage users']);
$role->givePermissionTo($permission);

// Assign role to user
$user = App\Models\User::find(1);
$user->assignRole('admin');
```

## 🗄 Database Structure

Citadel includes a well-structured database schema designed for scalability and security:

### 📊 Core Tables

| Table | Purpose | Key Features |
|-------|---------|--------------|
| `users` | User authentication and profiles | Email verification, timestamps |
| `password_reset_tokens` | Password reset functionality | Secure token-based reset |
| `sessions` | User session management | IP tracking, user agent logging |

### 🔐 OAuth2 Tables (Laravel Passport)

| Table | Purpose | Description |
|-------|---------|-------------|
| `oauth_auth_codes` | Authorization codes | Temporary codes for OAuth flow |
| `oauth_access_tokens` | API access tokens | Long-lived authentication tokens |
| `oauth_refresh_tokens` | Token refresh | Refresh expired access tokens |
| `oauth_clients` | OAuth clients | Registered applications |
| `oauth_device_codes` | Device authorization | Device flow support |

### 🚀 Performance & Caching Tables

| Table | Purpose | Features |
|-------|---------|----------|
| `cache` | Application cache | Key-value caching system |
| `jobs` | Background job queue | Retry logic, failure handling |

### 📁 Media Tables (Spatie Medialibrary)

When using the media library package, additional tables are created:

| Table | Purpose | Features |
|-------|---------|----------|
| `media` | File metadata | MIME types, sizes, conversions |

### 🔑 Permission Tables (Spatie Permission)

For role-based access control:

| Table | Purpose | Features |
|-------|---------|----------|
| `roles` | User roles | Hierarchical roles |
| `permissions` | System permissions | Granular access control |
| `role_has_permissions` | Role-permission mapping | Many-to-many relationship |
| `model_has_roles` | User-role assignment | Polymorphic relationships |

### 🔍 Search Tables (Laravel Scout)

Scout may create additional tables depending on the driver used.

## 🔧 Configuration

### Citadel Configuration

Citadel includes a comprehensive configuration system that allows you to customize behavior without modifying core code. The configuration is stored in `config/citadel.php`.

#### Key Configuration Options

```env
# Citadel Configuration - Add to your .env file

# Super Admin Role Name - This role will have all permissions
CITADEL_SUPER_ADMIN_ROLE="Super Admin"

# Default User Role - Assigned to new users upon registration  
CITADEL_DEFAULT_USER_ROLE="User"

# Permission Guard - Should match your authentication guard
CITADEL_PERMISSION_GUARD="api"

# Search Configuration
CITADEL_SEARCH_PER_PAGE=15
CITADEL_SEARCH_MAX_RESULTS=100

# API Configuration
CITADEL_API_PER_PAGE=15
CITADEL_API_MAX_PER_PAGE=100
CITADEL_API_RATE_LIMIT=60

# Authentication Configuration (in hours/days/minutes)
CITADEL_TOKEN_EXPIRATION_HOURS=24
CITADEL_REFRESH_TOKEN_EXPIRATION_DAYS=30
CITADEL_PASSWORD_RESET_EXPIRATION_MINUTES=60
```

#### Helper Functions

Citadel provides convenient helper functions for accessing configuration:

```php
// Get any citadel configuration value
$value = citadel_config('super_admin_role', 'Default');

// Get the super admin role name
$role = super_admin_role(); // Returns configured super admin role

// Get the default user role
$role = default_user_role(); // Returns configured default role

// Check if user is super admin
if (is_super_admin($user)) {
    // User has super admin privileges
}

// Get the permission guard
$guard = permission_guard(); // Returns configured guard
```

### Environment Variables

Key environment variables to configure:

```env
APP_NAME=Citadel
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite

# Redis Configuration (for caching, sessions, and queues)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0

# Cache Configuration
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Search Configuration
SCOUT_DRIVER=database
```

### Redis Configuration

Citadel uses Redis for high-performance caching, session storage, and queue management. Configure Redis in your `.env` file:

```env
# Basic Redis configuration
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null

# Use Redis for different services
CACHE_DRIVER=redis
SESSION_DRIVER=redis  
QUEUE_CONNECTION=redis

# Redis database assignments
REDIS_CACHE_DB=1
REDIS_SESSION_DB=2
REDIS_QUEUE_DB=3
```

For production environments, consider using Redis clusters or Sentinel for high availability.

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
