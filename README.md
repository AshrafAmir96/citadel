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

## 🎯 About Citadel

Citadel is a **production-ready Laravel backend boilerplate** designed to accelerate your web application development. Built with **Laravel 12** and modern **PHP 8.2+**, it provides a robust foundation with enterprise-grade features including OAuth2 authentication, role-based permissions, media management, and full-text search capabilities.

### ✨ Why Choose Citadel?

- ⚡ **Fast Setup** - Get your API up and running in minutes
- 🏗️ **Production Ready** - Built with scalability and security in mind  
- 🔒 **Enterprise Security** - OAuth2, RBAC, and security best practices
- 🧪 **Test Driven** - Comprehensive testing suite with Pest PHP
- 🐳 **Docker Ready** - Complete containerization for development and deployment
- 🚀 **CI/CD Included** - GitLab pipeline for automated testing and deployment
- 📚 **Well Documented** - Extensive documentation and examples

## 🚀 Quick Start

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

## ✨ Key Features

### 🔐 Authentication & Authorization
- **Laravel Passport OAuth2** - Complete OAuth2 server implementation
- **Role-Based Access Control** - Spatie Laravel Permission with dot notation (`users.*`, `media.*`)
- **Configurable Super Admin** - Environment-configurable super admin role
- **JWT Token Authentication** - Secure API authentication
- **Permission Helper Functions** - Wildcard-aware permission checking

### 🎯 Modern API Design
- **Query Builder Integration** - Spatie Laravel Query Builder for flexible API queries
- **Advanced Filtering** - `?filter[name]=john&sort=-created_at&include=roles`
- **Field Selection** - `?fields[users]=id,name,email` for optimized responses  
- **Auto-Generated Docs** - Scramble for automatic OpenAPI documentation
- **Consistent Responses** - Standardized JSON API response format

### 📁 Media & Content Management
- **File Upload System** - Spatie Laravel Medialibrary integration
- **Image Processing** - Automatic optimization and thumbnail generation
- **Full-Text Search** - Laravel Scout with multiple drivers
- **Multiple Storage** - Local, S3, and cloud storage support

### 🧪 Developer Experience
- **Pest PHP Testing** - Modern testing framework with beautiful syntax
- **Docker Development** - One-command environment setup
- **Code Quality Tools** - Laravel Pint, PHPStan integration
- **Hot Module Replacement** - Fast development with Vite
- **Redis Integration** - High-performance caching and sessions

## 📦 Key Technologies

| Component | Technology | Purpose |
|-----------|------------|---------|
| **Framework** | Laravel 12 | Backend foundation |
| **Authentication** | Laravel Passport | OAuth2 server |
| **Permissions** | Spatie Permission | Role-based access control |
| **Media** | Spatie Medialibrary | File management |
| **Search** | Laravel Scout | Full-text search |
| **API Queries** | Spatie Query Builder | Flexible API filtering |
| **Testing** | Pest PHP | Modern testing framework |
| **Frontend** | Tailwind CSS + Vite | Modern UI development |
| **Cache/Queue** | Redis | High-performance data store |
| **Database** | MySQL/PostgreSQL/SQLite | Flexible database support |

## 🏗️ Project Structure

### API Controllers (Organized)
```
app/Http/Controllers/Api/
├── AuthController.php          # Authentication endpoints
├── UserController.php          # User management with QueryBuilder
├── MediaController.php         # File upload and management
├── SearchController.php        # Full-text search functionality
└── ApiDocumentationController.php # API documentation
```

### Configuration System
- **`config/citadel.php`** - Centralized configuration
- **`app/helpers.php`** - Helper functions with autoloading
- **`.env.citadel.example`** - Environment variable examples


## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com) - The web artisans framework
- [Spatie](https://spatie.be) - Amazing Laravel packages
- [Pest PHP](https://pestphp.com) - Modern testing framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com) - The web artisans framework
- [Spatie](https://spatie.be) - Amazing Laravel packages
- [Pest PHP](https://pestphp.com) - Modern testing framework
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
