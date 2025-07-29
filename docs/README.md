# 📚 Citadel Documentation

Welcome to the comprehensive documentation for Citadel, a production-ready Laravel backend boilerplate.

## 🚀 Getting Started

### Quick Setup
- **[Quick Start Guide](../QUICKSTART.md)** - Get running in under 5 minutes
- **[Installation Guide](../README.md#quick-start)** - Detailed setup instructions
- **[Docker Guide](../DOCKER.md)** - Docker development environment

### First Steps
1. **Setup Environment**: Follow the [Quick Start Guide](../QUICKSTART.md)
2. **Create Super Admin**: Use `php artisan citadel:create-super-admin`
3. **Test API**: Visit http://localhost:8000/api/health
4. **Explore Features**: Check out the API documentation at http://localhost:8000/docs/api

## 📖 Core Documentation

### 🏰 Commands & Administration
- **[Commands Guide](COMMANDS.md)** - Complete guide to all Artisan commands
  - Super Admin Creation Command
  - Usage examples and troubleshooting
  - Docker integration

### 🚀 Deployment & Production
- **[Deployment Guide](DEPLOYMENT.md)** - Production deployment instructions
  - Traditional server deployment
  - Docker production setup
  - Cloud platform deployment
  - Security best practices
  - Monitoring and maintenance

### 🐳 Development Environment
- **[Docker Development](../DOCKER.md)** - Docker setup and usage
  - Service access points
  - Essential Docker commands
  - Troubleshooting

## 🎯 Feature Documentation

### 🔐 Authentication & Authorization
- **OAuth2 with Laravel Passport** - Complete OAuth2 server implementation
- **Role-Based Access Control** - Spatie Laravel Permission integration
- **Super Admin Management** - Dedicated command for admin user creation
- **JWT Token Authentication** - Secure API authentication

### 🗃️ Database & Models
- **User Management** - User model with roles and permissions
- **Media Management** - File upload and processing with Spatie Medialibrary
- **Full-Text Search** - Laravel Scout with Meilisearch integration
- **Database Seeding** - Comprehensive seeders for roles and permissions

### 🌐 API Features
- **Query Builder Integration** - Flexible API queries with Spatie Query Builder
- **Advanced Filtering** - Support for complex filtering, sorting, and field selection
- **Auto-Generated Documentation** - Scramble integration for OpenAPI docs
- **Consistent Response Format** - Standardized JSON API responses

## 🧪 Testing & Quality

### Testing Framework
- **Pest PHP** - Modern testing framework with beautiful syntax
- **Feature Tests** - Comprehensive API endpoint testing
- **Unit Tests** - Individual component testing
- **Docker Test Environment** - Isolated testing with Docker

### Code Quality
- **Laravel Pint** - Opinionated PHP code style fixer
- **PHPStan** - Static analysis for better code quality
- **ESLint & Prettier** - JavaScript/TypeScript code formatting

## 🛠️ Configuration

### Environment Configuration
- **Environment Variables** - Complete `.env` configuration guide
- **Citadel Configuration** - Custom configuration options in `config/citadel.php`
- **Service Configuration** - Database, cache, mail, and search service setup

### Customization
- **Helper Functions** - Utility functions for common tasks
- **Permission System** - Dot notation permissions with wildcard support
- **Role Management** - Configurable role names and permissions

## 📦 Package Integration

### Core Packages
- **Laravel Passport** - OAuth2 authentication server
- **Spatie Laravel Permission** - Role and permission management
- **Spatie Laravel Medialibrary** - File and media management
- **Spatie Laravel Query Builder** - Flexible API query building
- **Laravel Scout** - Full-text search capabilities

### Development Packages
- **Pest PHP** - Testing framework
- **Laravel Pint** - Code style fixer
- **Scramble** - API documentation generator
- **Laravel Tinker** - Powerful REPL for Laravel

## 🔧 Troubleshooting

### Common Issues
- **Docker Issues** - Container startup and permission problems
- **Database Issues** - Connection and migration problems
- **Authentication Issues** - OAuth and permission problems
- **Performance Issues** - Caching and optimization

### Getting Help
- **GitHub Issues** - Report bugs and request features
- **Documentation** - Comprehensive guides and examples
- **Community** - Join discussions and get help
- **Professional Support** - Commercial support options

## 📝 API Reference

### Endpoints
- **Authentication** - Login, logout, and token management
- **User Management** - CRUD operations with role management
- **Media Management** - File upload, processing, and retrieval
- **Search** - Full-text search across all content

### API Documentation
- **Interactive Docs** - Available at `/docs/api` when running
- **OpenAPI Specification** - Auto-generated with Scramble
- **Postman Collection** - Available for download
- **cURL Examples** - Command-line usage examples

## 🔄 Updates & Changelog

- **[Changelog](../CHANGELOG.md)** - All notable changes and version history
- **Migration Guides** - Upgrading between versions
- **Breaking Changes** - Important changes that require attention

## 🤝 Contributing

### Development Workflow
- **Fork and Clone** - Set up your development environment
- **Branching Strategy** - Feature branches and pull requests
- **Code Standards** - Follow Laravel and PSR standards
- **Testing Requirements** - Ensure all tests pass

### Contribution Guidelines
- **Bug Reports** - How to report issues effectively
- **Feature Requests** - Proposing new features
- **Pull Requests** - Code contribution guidelines
- **Documentation** - Improving and updating docs

## 📄 License & Legal

- **MIT License** - Open source license terms
- **Third-Party Licenses** - Included package licenses
- **Privacy Policy** - Data handling and privacy
- **Terms of Service** - Usage terms and conditions

---

## 🎯 Quick Links

| Document | Purpose | Audience |
|----------|---------|----------|
| [README.md](../README.md) | Project overview and quick start | All users |
| [QUICKSTART.md](../QUICKSTART.md) | 5-minute setup guide | New users |
| [DOCKER.md](../DOCKER.md) | Docker development setup | Developers |
| [Commands Guide](COMMANDS.md) | Artisan commands reference | Administrators |
| [Deployment Guide](DEPLOYMENT.md) | Production deployment | DevOps/Admins |
| [Changelog](../CHANGELOG.md) | Version history and changes | All users |

## 🆘 Need Help?

1. **Check the Documentation** - Most questions are answered here
2. **Search GitHub Issues** - Someone might have had the same problem
3. **Join Discussions** - Community help and feature discussions
4. **Contact Support** - Professional support for complex issues

**Welcome to Citadel! Build amazing applications with confidence! 🏰**
