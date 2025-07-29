# Changelog

All notable changes to the Citadel Laravel Boilerplate will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Initial release preparation for Packagist

## [1.1.0] - 2025-07-29

### Added
- **Citadel Configuration System**: Comprehensive configuration management with environment variable support
- **Dot Notation Permissions**: Flexible permission system with wildcard support (e.g., `users.*`, `media.*`)
- **Spatie Query Builder Integration**: Advanced API querying with filtering, sorting, field selection, and relationship inclusion
- **Permission Helper Functions**: Wildcard-aware permission checking with `can_wildcard()` and `authorize_wildcard()`
- **Configurable Super Admin Role**: Environment-configurable super admin role name via `CITADEL_SUPER_ADMIN_ROLE`
- **Enhanced Database Seeder**: Role and permission seeder with configurable role names and wildcard permissions
- **API Controller Organization**: Moved all API controllers to `App\Http\Controllers\Api` namespace for better organization
- **Comprehensive Helper Functions**: Utility functions for configuration access and permission management

### Changed
- **Permission Structure**: Migrated from space-separated to dot notation permissions (e.g., `manage users` → `users.manage`)
- **API Routes**: Updated to use organized Api controller namespace
- **UserController**: Enhanced with Spatie Query Builder for flexible API queries
- **AppServiceProvider**: Updated to use configurable super admin role name
- **Database Seeders**: Enhanced to support configurable role names and wildcard permissions

### Enhanced
- **API Querying**: Added support for advanced query parameters:
  - Filtering: `?filter[name]=john&filter[email]=example.com`
  - Sorting: `?sort=name,-created_at`
  - Field Selection: `?fields[users]=id,name,email`
  - Relationship Inclusion: `?include=roles,permissions`
  - Pagination: `?page[size]=10&page[number]=2`
- **Permission Management**: Wildcard permissions allow efficient role management
- **Configuration Flexibility**: Environment-based configuration for all major settings

### Documentation
- **CITADEL_CONFIG.md**: Complete configuration system documentation
- **DOT_NOTATION_PERMISSIONS.md**: Detailed permission system implementation guide
- **.env.citadel.example**: Example environment variables for easy setup
- **README.md**: Updated with Citadel configuration and QueryBuilder usage examples

### Files Added
- `config/citadel.php` - Main configuration file
- `app/helpers.php` - Helper functions with autoload support
- `database/seeders/RolesAndPermissionsSeeder.php` - Enhanced seeder with wildcard permissions
- `CITADEL_CONFIG.md` - Configuration documentation
- `DOT_NOTATION_PERMISSIONS.md` - Permission system guide
- `.env.citadel.example` - Environment variable examples

### Files Modified
- `app/Providers/AppServiceProvider.php` - Configurable super admin role support
- `app/Http/Controllers/Api/UserController.php` - QueryBuilder integration and dot notation permissions
- `app/Http/Controllers/Api/MediaController.php` - Dot notation permissions
- `database/seeders/DatabaseSeeder.php` - Enhanced with configurable roles
- `routes/api.php` - Updated for Api namespace controllers
- `composer.json` - Added helper functions autoloading
- `README.md` - Comprehensive documentation updates

## [1.0.0] - 2025-07-28

### Added
- **Core Framework**: Laravel 12 with PHP 8.2+ support
- **Authentication**: Laravel Passport OAuth2 server implementation
- **Authorization**: Spatie Laravel Permission for role-based access control
- **Media Management**: Spatie Laravel Medialibrary for file handling
- **Search**: Laravel Scout integration with multiple drivers
- **API Documentation**: Scramble for automatic OpenAPI documentation generation
- **Query Building**: Spatie Laravel Query Builder for flexible API queries
- **Caching**: Redis integration with Predis client
- **Testing**: Pest PHP framework with comprehensive test suite
- **Code Quality**: Laravel Pint for code styling
- **Development Tools**: Laravel Pail for log monitoring
- **Frontend**: Tailwind CSS 4.0 with Vite build system
- **Docker**: Complete containerization for development and production
- **CI/CD**: GitLab pipeline for automated testing and deployment
- **Database**: Support for MySQL, PostgreSQL, and SQLite
- **Queue System**: Redis-based job queuing
- **Email Testing**: MailHog integration for development
- **Database Management**: phpMyAdmin and Redis Commander tools

### Features
- **Production Ready**: Optimized for scalability and performance
- **Security First**: CSRF protection, SQL injection prevention, XSS protection
- **API-First Design**: RESTful API with consistent response format
- **Multi-Environment**: Development, staging, and production configurations
- **Health Checks**: Application monitoring and status endpoints
- **Backup System**: Automated database and file backup scripts
- **Hot Module Replacement**: Fast development feedback with Vite
- **Concurrent Development**: Server, queue worker, and asset building
- **Role-Based Permissions**: Granular access control system
- **File Upload System**: Secure file handling with type validation
- **Image Processing**: Automatic optimization and thumbnail generation
- **Full-Text Search**: Advanced search capabilities
- **Auto-Indexing**: Automatic content indexing and synchronization

### Developer Experience
- **Comprehensive Documentation**: Detailed README with examples
- **Docker Support**: One-command development environment setup
- **Testing Suite**: Feature and unit tests with coverage reporting
- **Code Quality Tools**: Automated linting and static analysis
- **Git Hooks**: Pre-commit quality checks
- **CLI Tools**: Custom Composer scripts for common tasks

### Infrastructure
- **GitLab CI/CD**: Automated testing, building, and deployment
- **Multi-Stage Deployments**: Staging, production, and review apps
- **Rollback Capabilities**: Automated backup and rollback system
- **Performance Monitoring**: Application health checks and metrics
- **Security Scanning**: Dependency audits and SAST integration

## [0.1.0] - 2025-07-28

### Added
- Initial project setup
- Basic Laravel 12 installation
- Docker development environment
- Core package selection and integration

---

## Release Process

1. Update version in `composer.json`
2. Update this CHANGELOG.md
3. Commit changes: `git commit -m "Release vX.Y.Z"`
4. Create and push tag: `git tag -a vX.Y.Z -m "Release version X.Y.Z" && git push origin vX.Y.Z`
5. Packagist will automatically update

## Versioning Strategy

- **Major (X.0.0)**: Breaking changes, Laravel version updates
- **Minor (X.Y.0)**: New features, new package additions
- **Patch (X.Y.Z)**: Bug fixes, security updates, documentation improvements

## Support

- **Laravel 12+**: Current supported version
- **PHP 8.2+**: Minimum required version
- **Node.js 18+**: For frontend asset building
- **Redis 6+**: For caching and queues
- **MySQL 8.0+ / PostgreSQL 13+ / SQLite 3.8+**: Database support
