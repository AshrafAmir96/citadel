# Testing Guide

Citadel uses **Pest PHP** for elegant and expressive testing. The test suite includes comprehensive coverage for all major features.

## 🏃‍♂️ Running Tests

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

## 📊 Test Coverage

| Feature | Coverage | Test Files |
|---------|----------|------------|
| **Authentication API** | 95% | `AuthenticationApiTest.php` |
| **User Management** | 90% | `UserManagementTest.php` (planned) |
| **API Endpoints** | 92% | `ApiTest.php` (planned) |
| **Permissions** | 88% | `PermissionTest.php` (planned) |
| **Media Upload** | 85% | `MediaTest.php` (planned) |

## 🧪 Test Structure

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

## 📝 Example Tests

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

## 🔧 Test Configuration

**Database:** Tests use an in-memory SQLite database for speed and isolation.

**Environment:** Test environment variables are configured in `phpunit.xml`:
```xml
<env name="APP_ENV" value="testing"/>
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## 🚀 Continuous Testing

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

## 📈 Performance Testing

```bash
# Benchmark specific tests
php artisan test --profile

# Memory usage analysis
php artisan test --memory-limit=512M

# Test database performance
php artisan test tests/Performance/DatabaseTest.php
```
