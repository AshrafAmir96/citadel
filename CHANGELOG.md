# 1.0.0 (2025-07-30)


### Bug Fixes

* Lower minimum test coverage requirement from 80% to 40% ([e7c9f0b](https://github.com/AshrafAmir96/citadel/commit/e7c9f0b3f5eba6202d47c6fa534ea55c6791c12f))
* Refactor app_version function for improved readability and consistency ([89ff066](https://github.com/AshrafAmir96/citadel/commit/89ff0662d57d25060d988d252fb3c70c5baad883))
* Remove obsolete error patterns from phpstan.neon configuration ([bb38536](https://github.com/AshrafAmir96/citadel/commit/bb38536735a811133e4e414a9ed9bb6a8ba4b340))
* resolve Passport client setup for CI/CD ([480f71e](https://github.com/AshrafAmir96/citadel/commit/480f71ed137ccc72312f167d1101fae5b79bb9d3))
* update homepage URL in composer.json ([f69660d](https://github.com/AshrafAmir96/citadel/commit/f69660da3b8a2465a1da01c2b378eaec2fd49da8))
* update project name and author details in composer.json ([24f62af](https://github.com/AshrafAmir96/citadel/commit/24f62afcfa02efb797966d61a3187e7a7b9d1b00))


### Features

* Add .releaserc.gitlab.json configuration for semantic-release ([e1aee18](https://github.com/AshrafAmir96/citadel/commit/e1aee18382961c767b936bcf81268583b0b0bc7d))
* Add GitHub Actions CI/CD integration and platform switcher ([c20157f](https://github.com/AshrafAmir96/citadel/commit/c20157f194fc0113bac9235c937966a925a4469f))
* add Meilisearch PHP client dependency and update Docker configurations ([809185e](https://github.com/AshrafAmir96/citadel/commit/809185ec42a24ee4daaf86d1e287994b403bf4ab))
* Add role management command with detailed statistics and output formats ([0e6a1cf](https://github.com/AshrafAmir96/citadel/commit/0e6a1cf179b477e9d7a76ee1a957881cc1281d12))
* Add semantic-release plugins for commit analysis and release notes generation ([e32ddb5](https://github.com/AshrafAmir96/citadel/commit/e32ddb561637bac8d6a53ac04debf08e8e92ff8f))
* Enable wildcard permissions in configuration ([0b4d0ee](https://github.com/AshrafAmir96/citadel/commit/0b4d0ee793b4433a69c5244767555c605449a3ae))
* enhance development environment by adding additional build tools and dependencies in Dockerfile ([246c4d2](https://github.com/AshrafAmir96/citadel/commit/246c4d2372c4872eab4112422f2025475796160f))
* Enhance Passport setup in tests and update CI/CD workflow for artifact uploads ([e0adea0](https://github.com/AshrafAmir96/citadel/commit/e0adea07a3433175e3e7e0badbc37e0eecf9bb33))
* enhance user retrieval with Query Builder support for filtering, sorting, and pagination ([fe5fa6d](https://github.com/AshrafAmir96/citadel/commit/fe5fa6d5f7881fa214c544fddbea49007490205a))
* implement comprehensive configuration system with dot notation permissions and role management ([e696e35](https://github.com/AshrafAmir96/citadel/commit/e696e35581c88c1ed37e630ae9220aae2df4d7ab))
* implement semantic versioning with GitLab CI/CD; add version endpoint and app_version() helper function ([d9b1212](https://github.com/AshrafAmir96/citadel/commit/d9b12123e5737bbe1a81ca1f44867ab03e580fd8))
* Refactor authentication logic and update testing environment configuration ([c83abfe](https://github.com/AshrafAmir96/citadel/commit/c83abfe613046497d1fdc422a154e208178557a4))
* update CHANGELOG.md with details for version 1.1.0, including new features, changes, enhancements, and documentation updates ([90b7da0](https://github.com/AshrafAmir96/citadel/commit/90b7da09db4d99621a3115d219933cced537ac50))
* Update CI/CD configurations for PHPStan with custom settings and add phpstan.neon file ([46de939](https://github.com/AshrafAmir96/citadel/commit/46de9395e7a06e4022ee3d7333eef51c98f47319))
* Update CI/CD pipeline to install semantic-release dependencies ([2b1e329](https://github.com/AshrafAmir96/citadel/commit/2b1e32920e63fc229c9a7f5d8c988154525fa36e))
* Update CI/CD pipeline to use npx for semantic-release and install dependencies ([8c2155a](https://github.com/AshrafAmir96/citadel/commit/8c2155ada0981b11f57dcae907efe25e6728bf2a))
* update entrypoint script for stable database migrations and cache clearing; add deployment script for Docker ([73f879c](https://github.com/AshrafAmir96/citadel/commit/73f879c557589971630e59769000ad23e293a159))
* Update media upload permissions and enhance media retrieval with pagination support ([df37797](https://github.com/AshrafAmir96/citadel/commit/df377975562fa0b6636e168994d75e4521e2e15c))

# Changelog

All notable changes to the Citadel Laravel Boilerplate will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **🏷️ Semantic Versioning Integration**: Automated version management with GitLab CI/CD
  - Semantic-release for automatic version bumps based on conventional commits
  - Version API endpoint (`GET /api/version`) for runtime version information
  - `app_version()` helper function with intelligent version detection
  - Conventional commit message format enforcement
  - Automated changelog generation and GitLab releases
  - Version-aware deployment process with versioned artifacts
  - Comprehensive semantic versioning documentation ([SEMANTIC_VERSIONING.md](SEMANTIC_VERSIONING.md))
- **🐙 GitHub Actions Integration**: Complete CI/CD pipeline for GitHub repositories
  - Mirror functionality of GitLab CI/CD with GitHub-native features
  - Automated testing, security scanning, and deployment workflows
  - GitHub-specific semantic release configuration
  - Environment protection with manual approval for production
  - CodeQL security analysis and dependency auditing
  - Comprehensive GitHub Actions documentation ([GITHUB_ACTIONS.md](GITHUB_ACTIONS.md))
- **🔄 CI/CD Platform Switcher**: Utility script for switching between GitLab and GitHub CI/CD
  - Easy switching between GitLab CI/CD and GitHub Actions
  - Automatic configuration backup and restoration
  - Status checking for current CI/CD setup
  - Platform-specific semantic release configuration management
- **🏷️ Role Management Command**: `citadel:get-role` for comprehensive role information
  - Beautiful table output with role icons and color coding
  - Multiple output formats (table, JSON, plain text)
  - Detailed permission breakdowns grouped by categories
  - Role statistics including user counts and usage analytics
  - Filtering options by role name and guard
  - Docker and Laravel Sail compatibility
- Initial release preparation for Packagist

## [1.2.0] - 2025-07-29

### Added
- **🏰 Super Admin Creation Command**: Interactive Artisan command `citadel:create-super-admin` for creating super administrator users
  - Beautiful table formatting with confirmation prompts
  - Email uniqueness validation and password strength requirements
  - Automatic "Super Admin" role assignment with all permissions
  - Docker and Laravel Sail compatibility
  - Success feedback with user details and helpful next steps
- **Command Documentation**: Comprehensive documentation in `COMMANDS.md` with usage examples and troubleshooting
- **Enhanced README**: Updated quick start guides to include super admin creation steps
- **Docker Integration**: Full support for super admin creation in containerized environments

### Fixed
- **Docker Supervisor Configuration**: Updated supervisord to use `/tmp/supervisor` for log files to resolve permission issues
- **Container Stability**: Improved Docker container startup reliability
- **Database Migration Conflicts**: Resolved duplicate migration issues for clean database setup

### Changed
- **User Model**: Improved compatibility with Laravel Scout and Meilisearch integration
- **Entrypoint Script**: Enhanced Docker entrypoint for better error handling and targeted seeding

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
