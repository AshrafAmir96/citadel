# Contributing to Citadel Laravel Boilerplate

Thank you for considering contributing to Citadel! This document provides guidelines and information for contributors.

## 🤝 How to Contribute

### Reporting Issues

**Before creating an issue:**
- Check existing issues to avoid duplicates
- Use the search function to find related discussions
- Provide detailed information about your environment

**When creating an issue:**
- Use a clear, descriptive title
- Include steps to reproduce the problem
- Provide error messages and logs
- Include your environment details (OS, PHP version, Laravel version)
- Add relevant labels (bug, enhancement, question, etc.)

### Suggesting Enhancements

**Enhancement suggestions should include:**
- Clear description of the proposed feature
- Use cases and benefits
- Possible implementation approach
- Any breaking changes considerations

### Pull Requests

**Before submitting a PR:**
1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Update documentation if necessary
7. Commit with clear messages

**PR Requirements:**
- [ ] Tests pass (`composer test`)
- [ ] Code follows PSR-12 style (`composer run pint`)
- [ ] Documentation updated if necessary
- [ ] CHANGELOG.md updated for significant changes
- [ ] PR description explains changes clearly

## 🛠 Development Setup

### Local Development

1. **Clone and setup:**
   ```bash
   git clone https://github.com/your-username/citadel-laravel-boilerplate.git
   cd citadel-laravel-boilerplate
   composer install
   npm install
   cp .env.example .env
   php artisan key:generate
   ```

2. **Database setup:**
   ```bash
   php artisan migrate
   php artisan passport:install
   ```

3. **Start development:**
   ```bash
   composer run dev
   ```

### Docker Development

```bash
# Start Docker environment
docker-compose up -d

# Run tests in Docker
docker-compose exec app php artisan test

# Access container shell
docker-compose exec app sh
```

### Running Tests

```bash
# Run all tests
composer test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test tests/Feature/AuthenticationTest.php

# Run tests in Docker
docker-compose exec app php artisan test
```

### Code Quality

```bash
# Fix code style
composer run pint

# Run static analysis (if configured)
./vendor/bin/phpstan analyse

# Run tests before committing
composer test
```

## 📝 Coding Standards

### PHP Code Style

- Follow **PSR-12** coding standard
- Use **Laravel conventions** for naming and structure
- Run `composer run pint` before committing
- Add **type hints** where possible
- Write **PHPDoc comments** for complex methods

### Testing

- Write **tests for new features**
- Maintain **test coverage** above 80%
- Use **Pest PHP** syntax for new tests
- Follow **AAA pattern** (Arrange, Act, Assert)
- Test both **happy path and edge cases**

### Documentation

- Update **README.md** for new features
- Add **PHPDoc comments** for public methods
- Update **API documentation** for endpoint changes
- Include **code examples** where helpful

### Git Commit Messages

Use conventional commit format:

```
type(scope): description

[optional body]

[optional footer]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
```
feat(auth): add OAuth2 token refresh endpoint
fix(docker): resolve SQLite extension compilation issue
docs(readme): update installation instructions
test(api): add tests for user management endpoints
```

## 🏗 Architecture Guidelines

### Package Structure

```
app/
├── Http/
│   ├── Controllers/     # API controllers
│   ├── Middleware/      # Custom middleware
│   └── Requests/        # Form request validation
├── Models/              # Eloquent models
├── Services/            # Business logic services
└── Providers/           # Service providers

database/
├── factories/           # Model factories
├── migrations/          # Database migrations
└── seeders/            # Database seeders

tests/
├── Feature/            # Integration tests
└── Unit/               # Unit tests
```

### Best Practices

**Controllers:**
- Keep controllers thin
- Use form requests for validation
- Return consistent API responses
- Handle exceptions gracefully

**Models:**
- Use model relationships
- Implement proper accessors/mutators
- Add model factories for testing
- Use model events when appropriate

**Services:**
- Extract complex business logic
- Make services testable
- Use dependency injection
- Follow single responsibility principle

**Tests:**
- Test public interfaces, not implementation
- Use factories for test data
- Mock external dependencies
- Test error conditions

## 🚀 Release Process

### Version Numbers

We follow [Semantic Versioning](https://semver.org/):
- **MAJOR**: Breaking changes
- **MINOR**: New features (backwards compatible)
- **PATCH**: Bug fixes (backwards compatible)

### Release Checklist

- [ ] Update version in `composer.json`
- [ ] Update `CHANGELOG.md`
- [ ] Run full test suite
- [ ] Update documentation
- [ ] Create git tag
- [ ] Push to GitHub
- [ ] Packagist auto-updates

## 🎯 Areas for Contribution

### High Priority
- **Additional Authentication Methods** (Social login, 2FA)
- **API Rate Limiting** improvements
- **Performance Optimizations**
- **Security Enhancements**
- **Database Performance** optimization

### Medium Priority
- **Additional Storage Drivers** (S3, Google Cloud)
- **Notification Channels** (Slack, Discord, SMS)
- **Monitoring Integration** (Sentry, New Relic)
- **Caching Strategies** improvements
- **Background Job** enhancements

### Documentation
- **Video Tutorials**
- **Code Examples**
- **Deployment guides**
- **Performance tuning guides**
- **Security best practices**

### Community
- **Example Applications**
- **Integration Guides**
- **Blog Posts**
- **Conference Talks**
- **Community Packages**

## 📧 Communication

### Getting Help

- **GitHub Issues**: For bugs and feature requests
- **GitHub Discussions**: For questions and community discussion
- **Discord/Slack**: Real-time community chat (if available)

### Code of Conduct

- Be respectful and inclusive
- Provide constructive feedback
- Help others learn and grow
- Follow the project's code of conduct

### Recognition

Contributors will be:
- Added to the contributors list
- Mentioned in release notes for significant contributions
- Credited in documentation where appropriate

## 🏆 Contributor Levels

### Contributor
- Submit issues and pull requests
- Help with documentation
- Participate in discussions

### Maintainer
- Review pull requests
- Manage releases
- Triage issues
- Guide project direction

### Core Team
- Full repository access
- Make architectural decisions
- Manage project roadmap
- Represent the project publicly

---

**Thank you for contributing to Citadel! 🚀**

Together, we can build an amazing Laravel boilerplate that helps developers create better applications faster.
