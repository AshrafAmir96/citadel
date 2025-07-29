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

<p align="center">
    <strong>🚀 Production-Ready Laravel Backend Boilerplate</strong><br>
    Built with Laravel 12, PHP 8.2+, and modern development practices
</p>

---

## 📋 Table of Contents

- [About Citadel](#-about-citadel)
- [Features](#-features)
- [Key Packages](#-key-packages)
- [Quick Start](#-quick-start)
- [Installation](#-installation)
- [Docker Development](#-docker-development)
- [Database Structure](#-database-structure)
- [Configuration](#-configuration)
- [API Documentation](#-api-documentation)
- [Testing](#-testing)
- [CI/CD Pipeline](#-cicd-pipeline)
- [Deployment](#-deployment)
- [Security Features](#-security-features)
- [Development Commands](#-development-commands)
- [Contributing](#-contributing)
- [License](#-license)
- [Acknowledgments](#-acknowledgments)</p>
</invoke>

## 🚀 About Citadel

Citadel is a **production-ready Laravel backend boilerplate** designed to accelerate your web application development. Built with **Laravel 12** and modern **PHP 8.2+**, it provides a robust foundation with enterprise-grade features including OAuth2 authentication, role-based permissions, media management, and full-text search capabilities.

### 🎯 Why Choose Citadel?

- ⚡ **Fast Setup** - Get your API up and running in minutes
- 🏗️ **Production Ready** - Built with scalability and security in mind  
- 🔒 **Enterprise Security** - OAuth2, RBAC, and security best practices
- 🧪 **Test Driven** - Comprehensive testing suite with Pest PHP
- 🐳 **Docker Ready** - Complete containerization for development and deployment
- 🚀 **CI/CD Included** - GitLab pipeline for automated testing and deployment
- 📚 **Well Documented** - Extensive documentation and examples

## ⚡ Quick Start

Get Citadel running in under 5 minutes:

### Option 1: Docker (Recommended)
```bash
git clone <repository-url> citadel
cd citadel
docker-compose up -d
```
**🌐 Access:** http://localhost:8000

### Option 2: Traditional Setup
```bash
git clone <repository-url> citadel
cd citadel
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate && php artisan passport:install
composer run dev
```
**🌐 Access:** http://localhost:8000

### Option 3: Laravel Sail
```bash
git clone <repository-url> citadel
cd citadel
./vendor/bin/sail up -d
```
**🌐 Access:** http://localhost

---

## ✨ Features

### 🔐 Authentication & Authorization
- **Laravel Passport OAuth2** - Complete OAuth2 server implementation
- **Role-Based Access Control** - Spatie Laravel Permission integration
- **JWT Token Authentication** - Secure API authentication
- **Multi-guard Authentication** - Support for different user types
- **Password Reset & Email Verification** - Built-in user management

### 📁 Media Management
- **File Upload System** - Spatie Laravel Medialibrary integration
- **Image Processing** - Automatic image optimization and resizing
- **Multiple Storage Drivers** - Local, S3, and cloud storage support
- **File Type Validation** - Secure file upload with type checking
- **Media Conversions** - Generate thumbnails and different formats

### 🔍 Search Capabilities
- **Full-Text Search** - Laravel Scout with multiple drivers
- **Search Engine Support** - Meilisearch, Algolia, and database drivers
- **Auto-Indexing** - Automatic content indexing and synchronization
- **Advanced Filtering** - Complex search queries and filters

### 📚 API Documentation & Query Building
- **Auto-Generated API Docs** - Scramble for automatic OpenAPI documentation
- **Elegant Query Builder** - Spatie Laravel Query Builder for flexible API queries
- **Request Filtering** - Advanced filtering, sorting, and field selection
- **API Versioning** - Built-in support for API versioning

### 🧪 Testing & Quality Assurance
- **Pest PHP Framework** - Modern PHP testing with beautiful syntax
- **Feature & Unit Tests** - Comprehensive test coverage
- **CI/CD Integration** - Automated testing pipeline
- **Code Quality Tools** - Laravel Pint, PHPStan integration
- **Test Coverage Reports** - Detailed coverage analysis

### 🎨 Frontend Integration
- **Tailwind CSS 4.0** - Modern utility-first CSS framework
- **Vite Build System** - Lightning-fast frontend tooling
- **Modern JavaScript** - ES6+ with Axios for HTTP requests
- **Hot Module Replacement** - Fast development feedback

### ⚡ Development Experience
- **Laravel Pint** - Automatic code style fixing
- **Laravel Pail** - Real-time log monitoring
- **Laravel Sail** - Docker development environment
- **Concurrent Servers** - Dev server, queue worker, and asset building
- **Git Hooks** - Pre-commit code quality checks
- **Redis Integration** - High-performance caching and session storage

### 🐳 DevOps & Deployment
- **Docker Support** - Complete containerization
- **GitLab CI/CD** - Automated testing and deployment
- **Multi-Environment** - Staging, production, and review apps
- **Health Checks** - Application monitoring and status
- **Backup System** - Automated database and file backups

## 📦 Key Packages

Citadel is built on top of carefully selected, production-tested packages:

### 🔧 Backend Core Dependencies

| Package | Version | Purpose | Documentation |
|---------|---------|---------|---------------|
| `laravel/framework` | ^12.0 | Core Laravel framework | [Docs](https://laravel.com/docs) |
| `laravel/passport` | ^13.0 | OAuth2 server implementation | [Docs](https://laravel.com/docs/passport) |
| `laravel/scout` | ^10.17 | Full-text search abstraction | [Docs](https://laravel.com/docs/scout) |
| `spatie/laravel-medialibrary` | ^11.13 | Media file management | [Docs](https://spatie.be/docs/laravel-medialibrary) |
| `spatie/laravel-permission` | ^6.21 | Role and permission management | [Docs](https://spatie.be/docs/laravel-permission) |
| `spatie/laravel-query-builder` | ^6.3 | Elegant API query builder | [Docs](https://spatie.be/docs/laravel-query-builder) |
| `predis/predis` | ^3.1 | Redis client for PHP | [Docs](https://github.com/predis/predis) |
| `dedoc/scramble` | ^0.12.26 | API documentation generator | [Docs](https://scramble.dedoc.co) |
| `laravel/tinker` | ^2.10.1 | Interactive PHP REPL | [Docs](https://laravel.com/docs/artisan#tinker) |

### 🛠 Development & Testing Dependencies

| Package | Version | Purpose | Documentation |
|---------|---------|---------|---------------|
| `pestphp/pest` | ^3.8 | Modern PHP testing framework | [Docs](https://pestphp.com) |
| `laravel/pint` | ^1.13 | PHP code style fixer | [Docs](https://laravel.com/docs/pint) |
| `laravel/sail` | ^1.41 | Docker development environment | [Docs](https://laravel.com/docs/sail) |
| `laravel/pail` | ^1.2.2 | Real-time log monitoring | [Docs](https://laravel.com/docs/logging#tailing-logs) |

### 🎨 Frontend Dependencies

| Package | Version | Purpose | Documentation |
|---------|---------|---------|---------------|
| `tailwindcss` | ^4.0.0 | Utility-first CSS framework | [Docs](https://tailwindcss.com) |
| `vite` | ^7.0.4 | Next-generation frontend tooling | [Docs](https://vitejs.dev) |
| `axios` | ^1.8.2 | Promise-based HTTP client | [Docs](https://axios-http.com) |
| `concurrently` | ^9.0.1 | Run multiple commands concurrently | [NPM](https://www.npmjs.com/package/concurrently) |

## 🛠 Implementation Status

### ✅ Completed Features

| Component | Status | Description |
|-----------|--------|-------------|
| **API Routes** | ✅ Complete | All routes defined in `routes/api.php` |
| **API Controllers** | ✅ Complete | Organized in `App\Http\Controllers\Api` namespace |
| **Authentication Controller** | ✅ Complete | Register, login, logout, current user |
| **User Management Controller** | ✅ Complete | CRUD operations with permission checks |
| **Media Controller** | ✅ Complete | File upload, list, and delete operations |
| **Search Controller** | ✅ Complete | Laravel Scout integration for user search |
| **API Documentation Controller** | ✅ Complete | Endpoint overview and documentation |
| **User Model Extensions** | ✅ Complete | Passport, Permissions, Media, Search traits |
| **Database Migrations** | ✅ Published | Permission and Media Library migrations |
| **API Tests** | ✅ Complete | Comprehensive authentication API tests |
| **Consistent Response Format** | ✅ Complete | Standardized JSON responses with error handling |

### 🏗️ Controllers Created

All API controllers are organized in the `App\Http\Controllers\Api` namespace for better structure and maintainability:

1. **`Api\AuthController`** - Authentication endpoints
   - `register()` - User registration with validation
   - `login()` - User authentication with OAuth2
   - `logout()` - Token revocation
   - `user()` - Get current authenticated user

2. **`Api\UserController`** - User management endpoints
   - `index()` - List all users (admin only)
   - `show()` - Get user by ID (with permission checks)
   - `update()` - Update user profile (with permission checks)
   - `assignRole()` - Assign role to user (admin only)
   - `permissions()` - Get user permissions

3. **`Api\MediaController`** - File management endpoints
   - `store()` - Upload files with validation (10MB limit)
   - `index()` - List user's media files
   - `destroy()` - Delete media files

4. **`Api\SearchController`** - Search functionality
   - `search()` - Search users with Laravel Scout

5. **`Api\ApiDocumentationController`** - API documentation
   - `index()` - Overview of all available endpoints

### 📁 Controller Structure

```
app/Http/Controllers/
├── Controller.php              # Base controller
└── Api/                       # API controllers namespace
    ├── ApiDocumentationController.php
    ├── AuthController.php
    ├── MediaController.php
    ├── SearchController.php
    └── UserController.php
```

### 🚧 Next Steps Required

To complete the setup and start using the API:

1. **Database Setup**
   ```bash
   # Configure database in .env file
   # For MySQL:
   DB_CONNECTION=mysql
   DB_DATABASE=citadel
   DB_USERNAME=root
   DB_PASSWORD=your_password
   
   # Run migrations
   php artisan migrate
   ```

2. **Install Passport**
   ```bash
   php artisan passport:install
   ```

3. **Create Initial Admin User**
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
   
   // Create admin role and permission
   $role = Spatie\Permission\Models\Role::create(['name' => 'admin']);
   $permission = Spatie\Permission\Models\Permission::create(['name' => 'manage users']);
   $role->givePermissionTo($permission);
   $user->assignRole('admin');
   ```

4. **Test the API**
   ```bash
   # Start the server
   php artisan serve
   
   # Run tests
   composer test
   
   # Test endpoints with curl or Postman
   curl -X GET http://localhost:8000/api/
   ```

## 📦 Package Installation

## � Package Installation

You can install this boilerplate via Composer:

```bash
# Create a new project using Citadel
composer create-project your-username/citadel-laravel-boilerplate my-project

# Navigate to your project
cd my-project

# Set up the environment
cp .env.example .env
php artisan key:generate

# Run database migrations
php artisan migrate

# Install Passport
php artisan passport:install

# Install frontend dependencies and build assets
npm install && npm run build

# Start development server
composer run dev
```

## �🛠 Installation

### 📋 Prerequisites

Ensure you have the following installed on your system:

| Requirement | Version | Download |
|-------------|---------|----------|
| **PHP** | 8.2+ | [php.net](https://www.php.net/downloads) |
| **Composer** | 2.0+ | [getcomposer.org](https://getcomposer.org) |
| **Node.js** | 18+ | [nodejs.org](https://nodejs.org) |
| **Database** | MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8+ | - |

### 🚀 Step-by-Step Installation

#### 1. Clone the Repository
```bash
git clone <repository-url> citadel
cd citadel
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies  
npm install
```

#### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### 4. Database Setup

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

#### 5. OAuth2 Setup
```bash
# Generate Passport keys
php artisan passport:install

# Optional: Create personal access client
php artisan passport:client --personal
```

#### 6. Asset Compilation
```bash
# Build frontend assets
npm run build

# Or for development with hot reload
npm run dev
```

#### 7. Start Development Server
```bash
# Option 1: Use the custom dev script (Recommended)
composer run dev

# Option 2: Individual commands
php artisan serve &
php artisan queue:work &
npm run dev
```

### 🔧 Post-Installation Setup

#### Create Admin User
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

#### Setup Permissions (Optional)
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

## 📚 API Documentation

Citadel provides a comprehensive RESTful API with OAuth2 authentication using Laravel Passport. All API endpoints follow consistent response formats and include proper error handling.

### 🚀 Quick API Overview

Access the API documentation endpoint for a complete overview:

```http
GET /api/
```

This returns a JSON response with all available endpoints and their descriptions.

### 🔐 Authentication Endpoints

#### Register User
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Success Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "message": "User registered successfully"
}
```

**Error Response (422):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "email": ["The email has already been taken."],
            "password": ["The password confirmation does not match."]
        }
    }
}
```

#### Login User
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password123"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer",
    "message": "User logged in successfully"
}
```

**Error Response (401):**
```json
{
    "success": false,
    "error": {
        "code": "AUTHENTICATION_ERROR",
        "message": "Invalid credentials"
    }
}
```

#### Logout User
```http
POST /api/auth/logout
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "User logged out successfully"
}
```

#### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": "2025-07-28T10:00:00.000000Z",
        "created_at": "2025-07-28T10:00:00.000000Z",
        "updated_at": "2025-07-28T10:00:00.000000Z"
    },
    "message": "User retrieved successfully"
}
```

### 👤 User Management Endpoints

#### Get All Users (Admin only)
```http
GET /api/users
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "created_at": "2025-07-28T10:00:00.000000Z"
            }
        ],
        "per_page": 15,
        "total": 1
    },
    "message": "Users retrieved successfully"
}
```

**Error Response (403 - Permission Denied):**
```json
{
    "success": false,
    "error": {
        "code": "PERMISSION_DENIED",
        "message": "You do not have permission to access this resource."
    }
}
```

#### Get User by ID
```http
GET /api/users/{id}
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "message": "User retrieved successfully"
}
```

**Error Response (404 - Not Found):**
```json
{
    "success": false,
    "error": {
        "code": "USER_NOT_FOUND",
        "message": "User not found."
    }
}
```

#### Update User Profile
```http
PUT /api/users/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "Updated Name",
    "email": "updated@example.com"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Updated Name",
        "email": "updated@example.com",
        "created_at": "2025-07-28T10:00:00.000000Z",
        "updated_at": "2025-07-28T11:00:00.000000Z"
    },
    "message": "User updated successfully"
}
```

### 🔑 Role & Permission Endpoints

#### Assign Role to User
```http
POST /api/users/{id}/roles
Authorization: Bearer {token}
Content-Type: application/json

{
    "role": "admin"
}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "roles": ["admin"]
    },
    "message": "Role assigned successfully"
}
```

**Error Response (422 - Invalid Role):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "role": ["The selected role is invalid."]
        }
    }
}
```

#### Get User Permissions
```http
GET /api/users/{id}/permissions
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "roles": ["admin", "editor"],
        "permissions": ["manage users", "edit posts", "view dashboard"],
        "direct_permissions": ["manage users"]
    },
    "message": "User permissions retrieved successfully"
}
```

### 📁 Media Management Endpoints

#### Upload File
```http
POST /api/media
Authorization: Bearer {token}
Content-Type: multipart/form-data

file: [binary data]
collection: "avatars"
```

**Success Response (201):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "avatar.jpg",
        "file_name": "avatar.jpg",
        "mime_type": "image/jpeg",
        "size": 2048576,
        "collection_name": "avatars",
        "url": "http://localhost:8000/storage/media/1/avatar.jpg",
        "created_at": "2025-07-28T10:00:00.000000Z"
    },
    "message": "File uploaded successfully"
}
```

**Error Response (422 - File Too Large):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "file": ["The file may not be greater than 10240 kilobytes."]
        }
    }
}
```

**Error Response (500 - Upload Failed):**
```json
{
    "success": false,
    "error": {
        "code": "UPLOAD_ERROR",
        "message": "File upload failed: Disk full"
    }
}
```

#### Get Media Files
```http
GET /api/media
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "avatar.jpg",
            "file_name": "avatar.jpg",
            "mime_type": "image/jpeg",
            "size": 2048576,
            "collection_name": "avatars",
            "url": "http://localhost:8000/storage/media/1/avatar.jpg",
            "created_at": "2025-07-28T10:00:00.000000Z"
        }
    ],
    "message": "Media files retrieved successfully"
}
```

#### Delete Media File
```http
DELETE /api/media/{id}
Authorization: Bearer {token}
```

**Success Response (200):**
```json
{
    "success": true,
    "message": "Media file deleted successfully"
}
```

**Error Response (404 - Media Not Found):**
```json
{
    "success": false,
    "error": {
        "code": "MEDIA_NOT_FOUND",
        "message": "Media file not found."
    }
}
```

### 🔍 Search Endpoints

#### Search Content
```http
GET /api/search?q={query}&limit=10&offset=0
Authorization: Bearer {token}
```

**Parameters:**
- `q` (required): Search query string (1-255 characters)
- `limit` (optional): Number of results to return (1-100, default: 10)
- `offset` (optional): Number of results to skip (default: 0)

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "results": [
            {
                "type": "user",
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "created_at": "2025-07-28T10:00:00.000000Z"
            }
        ],
        "query": "john",
        "limit": 10,
        "offset": 0,
        "total": 1
    },
    "message": "Search completed successfully"
}
```

**Error Response (422 - Invalid Query):**
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "q": ["The q field is required."]
        }
    }
}
```

**Error Response (500 - Search Failed):**
```json
{
    "success": false,
    "error": {
        "code": "SEARCH_ERROR",
        "message": "Search failed: Search service unavailable"
    }
}
```

### 📊 API Response Format

All API responses follow a consistent JSON structure for both success and error cases:

**Success Response Structure:**
```json
{
    "success": true,
    "data": { /* Response data */ },
    "message": "Operation completed successfully"
}
```

**Error Response Structure:**
```json
{
    "success": false,
    "error": {
        "code": "ERROR_CODE",
        "message": "Human-readable error message",
        "details": { /* Additional error details, typically validation errors */ }
    }
}
```

**Common Error Codes:**
- `VALIDATION_ERROR` - Input validation failed (422)
- `AUTHENTICATION_ERROR` - Invalid credentials (401)
- `PERMISSION_DENIED` - Insufficient permissions (403)
- `USER_NOT_FOUND` - User does not exist (404)
- `MEDIA_NOT_FOUND` - Media file does not exist (404)
- `UPLOAD_ERROR` - File upload failed (500)
- `SEARCH_ERROR` - Search operation failed (500)

**HTTP Status Codes:**
- `200` - Success
- `201` - Created (for registration and file uploads)
- `401` - Unauthorized (authentication required)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `422` - Unprocessable Entity (validation errors)
- `500` - Internal Server Error

### 🔒 API Security

- **OAuth2 Bearer Tokens** - All protected endpoints require valid Bearer token authentication
- **Role-Based Access Control** - Permission checks on sensitive operations (user management)
- **Input Validation** - All request data is validated using Laravel's form request validation
- **Rate Limiting** - API calls are rate-limited to prevent abuse (configurable)
- **CORS Support** - Cross-origin resource sharing with configurable origins
- **CSRF Protection** - Built-in CSRF token validation for web routes
- **SQL Injection Prevention** - Eloquent ORM with parameter binding
- **XSS Protection** - Automatic output escaping and input sanitization

### 📝 API Testing

#### Using cURL

**Register a new user:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Get user info (replace TOKEN with actual token):**
```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer TOKEN"
```

**Upload a file:**
```bash
curl -X POST http://localhost:8000/api/media \
  -H "Authorization: Bearer TOKEN" \
  -F "file=@/path/to/your/file.jpg" \
  -F "collection=uploads"
```

**Search users:**
```bash
curl -X GET "http://localhost:8000/api/search?q=john&limit=5" \
  -H "Authorization: Bearer TOKEN"
```

#### Using Postman

1. Create a new collection called "Citadel API"
2. Set up environment variables:
   - `baseUrl`: `http://localhost:8000`
   - `token`: `{{access_token}}` (will be set after login)
3. Create requests for each endpoint using the examples above
4. Use the login request to automatically set the token for subsequent requests

#### Automated Testing

The project includes comprehensive API tests written in Pest PHP:

```bash
# Run all API tests
php artisan test --filter=AuthenticationApiTest

# Run specific test
php artisan test --filter="user can register with valid data"

# Run tests with coverage
php artisan test --coverage --filter=AuthenticationApiTest
```

## 🧪 Testing

Citadel uses **Pest PHP** for elegant and expressive testing. The test suite includes comprehensive coverage for all major features.

### 🏃‍♂️ Running Tests

```bash
# Run all tests
composer test
# or
php artisan test

# Run tests with coverage
php artisan test --coverage

# Run specific test file
php artisan test tests/Feature/AuthenticationTest.php

# Run tests with specific filter
php artisan test --filter=user_can_register

# Run tests in parallel (faster)
php artisan test --parallel

# Run tests with detailed output
php artisan test --verbose
```

### 📊 Test Coverage

| Feature | Coverage | Test Files |
|---------|----------|------------|
| **Authentication API** | 95% | `AuthenticationApiTest.php` |
| **User Management** | 90% | `UserManagementTest.php` (planned) |
| **API Endpoints** | 92% | `ApiTest.php` (planned) |
| **Permissions** | 88% | `PermissionTest.php` (planned) |
| **Media Upload** | 85% | `MediaTest.php` (planned) |

### 🧪 Test Structure

```
tests/
├── Feature/           # Integration tests
│   ├── AuthenticationApiTest.php  ✅ Created
│   ├── UserManagementTest.php     📝 Planned
│   ├── ApiTest.php               📝 Planned
│   ├── MediaUploadTest.php       📝 Planned
│   └── ExampleTest.php           ✅ Default Laravel test
├── Unit/              # Unit tests
│   ├── UserTest.php              📝 Planned
│   ├── RoleTest.php              📝 Planned
│   ├── PermissionTest.php        📝 Planned
│   └── ExampleTest.php           ✅ Default Laravel test
├── Pest.php           # Pest configuration
└── TestCase.php       # Base test class
```

### 📝 Example Tests

The `AuthenticationApiTest.php` includes comprehensive tests for the authentication flow:

**Feature Test Example:**
```php
test('user can register with valid data', function () {
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ];

    $response = $this->postJson('/api/auth/register', $userData);

    $response->assertStatus(201)
             ->assertJsonStructure([
                 'success',
                 'data' => ['id', 'name', 'email', 'created_at'],
                 'access_token',
                 'token_type',
                 'message'
             ])
             ->assertJson([
                 'success' => true,
                 'token_type' => 'Bearer'
             ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
        'name' => 'John Doe'
    ]);
});
```

**Authentication Tests Coverage:**
- ✅ User registration with valid data
- ✅ User registration with invalid data  
- ✅ User login with valid credentials
- ✅ User login with invalid credentials
- ✅ Authenticated user can access protected endpoints
- ✅ Unauthenticated user cannot access protected endpoints
- ✅ User logout functionality

**Unit Test Example:**
```php
test('user can be assigned a role', function () {
    $user = User::factory()->create();
    $role = Role::create(['name' => 'admin']);

    $user->assignRole($role);

    expect($user->hasRole('admin'))->toBeTrue();
    expect($user->roles)->toHaveCount(1);
});
```

### 🔧 Test Configuration

**Database:** Tests use an in-memory SQLite database for speed and isolation.

**Environment:** Test environment variables are configured in `phpunit.xml`:
```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### 🚀 Continuous Testing

**Watch Mode** (automatically run tests on file changes):
```bash
# Install file watcher
npm install -g nodemon

# Watch and run tests
nodemon --ext php --exec "php artisan test"
```

**Pre-commit Hooks** (run tests before commits):
```bash
# Install git hooks
composer install

# Tests will run automatically before each commit
```

### 📈 Performance Testing

```bash
# Benchmark specific tests
php artisan test --profile

# Memory usage analysis
php artisan test --memory-limit=512M

# Test database performance
php artisan test tests/Performance/DatabaseTest.php
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

Docker provides a consistent development environment across all platforms.

### 🚀 Quick Start with Docker

```bash
# Clone and start the development environment
git clone <repository-url> citadel
cd citadel
docker-compose up -d

# Wait for services to be ready (30-60 seconds)
docker-compose logs -f app
```

### 🌐 Service Access Points

| Service | URL | Credentials | Purpose |
|---------|-----|-------------|---------|
| **Laravel App** | http://localhost:8000 | - | Main application |
| **phpMyAdmin** | http://localhost:8080 | `citadel/secret` | Database management |
| **MailHog** | http://localhost:8025 | - | Email testing |
| **Redis Commander** | http://localhost:8081 | - | Redis management |
| **Meilisearch** | http://localhost:7700 | `citadel_search_key` | Search dashboard |

### 🛠 Docker Services

| Service | Port | Container | Description |
|---------|------|-----------|-------------|
| `app` | 8000 | citadel-app | Laravel application (PHP 8.2-FPM) |
| `mysql` | 3306 | citadel-mysql | MySQL 8.0 database |
| `redis` | 6379 | citadel-redis | Redis 7 for cache & sessions |
| `meilisearch` | 7700 | citadel-meilisearch | Full-text search engine |
| `queue` | - | citadel-queue | Background job processing |
| `scheduler` | - | citadel-scheduler | Laravel task scheduling |
| `mailhog` | 1025/8025 | citadel-mailhog | SMTP testing server |

### ⚡ Essential Docker Commands

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

### 🔧 Advanced Docker Usage

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

### 🔧 Docker Troubleshooting

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
