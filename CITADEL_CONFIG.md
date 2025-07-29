# Citadel Configuration System

## Overview

Citadel includes a comprehensive configuration system that allows you to customize application behavior without modifying core code. The main configuration file is `config/citadel.php`.

## Quick Setup

1. **Copy environment variables** to your `.env` file:
```bash
cp .env.citadel.example .env
# Or manually add the variables to your existing .env file
```

2. **Customize the values** in your `.env` file:
```env
CITADEL_SUPER_ADMIN_ROLE="Administrator"
CITADEL_DEFAULT_USER_ROLE="Member"
```

3. **Run the seeder** to create roles and permissions:
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Configuration Options

### Role Management
- `CITADEL_SUPER_ADMIN_ROLE` - Name of the role that gets all permissions
- `CITADEL_DEFAULT_USER_ROLE` - Role assigned to new users
- `CITADEL_PERMISSION_GUARD` - Guard used for permissions (should match auth guard)

### API Settings
- `CITADEL_API_PER_PAGE` - Default pagination size for API responses
- `CITADEL_API_MAX_PER_PAGE` - Maximum allowed pagination size
- `CITADEL_API_RATE_LIMIT` - API rate limit per minute

### Search Configuration
- `CITADEL_SEARCH_PER_PAGE` - Default search results per page
- `CITADEL_SEARCH_MAX_RESULTS` - Maximum search results returned

### Authentication
- `CITADEL_TOKEN_EXPIRATION_HOURS` - OAuth token expiration time
- `CITADEL_REFRESH_TOKEN_EXPIRATION_DAYS` - Refresh token expiration
- `CITADEL_PASSWORD_RESET_EXPIRATION_MINUTES` - Password reset link expiration

## Permission System with Wildcard Support

Citadel uses dot notation permissions with wildcard support for flexible authorization:

### Permission Patterns

```php
// Specific permissions
'users.view'        // Can view users
'users.create'      // Can create users
'media.upload'      // Can upload media

// Wildcard permissions
'users.*'           // All user permissions
'media.*'           // All media permissions
'system.*'          // All system permissions

// Management permissions (implies all actions)
'users.manage'      // Manages all user operations
'roles.manage'      // Manages all role operations
```

### Helper Functions

```php
// Standard permission check
$user->can('users.view')

// Wildcard permission check (new)
can_wildcard($user, 'users.create')    // Also checks users.* and users.manage

// Authorize with wildcard support
authorize_wildcard('media.upload')     // Throws exception if not authorized
```

### Available Permission Groups

- **users.*** - User management (view, create, update, delete, manage)
- **roles.*** - Role management (view, create, update, delete, manage, assign)
- **permissions.*** - Permission management (view, create, update, delete, manage)
- **media.*** - Media management (view, upload, update, delete, manage)
- **system.*** - System management (manage, configure, backup, restore)
- **analytics.*** - Analytics (view, export)
- **api.*** - API access (access, admin)

## Helper Functions

### Configuration Helpers

```php
// Get any citadel config value
citadel_config('super_admin_role', 'Default Value')

// Get specific role names
super_admin_role()        // Returns configured super admin role
default_user_role()       // Returns configured default user role

// Check if user is super admin
is_super_admin($user)     // Returns true if user has super admin role

// Get permission guard
permission_guard()        // Returns configured permission guard
```

### Permission Helpers

```php
// Standard permission check
$user->can('users.view')

// Wildcard permission check
can_wildcard($user, 'users.create')    // Also checks users.* and users.manage

// Authorize with wildcard support
authorize_wildcard('media.upload')     // Throws exception if not authorized
```

### Usage Examples

```php
// In a controller
public function someMethod()
{
    if (is_super_admin()) {
        // Super admin logic
    }
    
    $newUser->assignRole(default_user_role());
}

// In a middleware
public function handle($request, Closure $next)
{
    if (!$request->user()->hasRole(super_admin_role())) {
        abort(403);
    }
    
    return $next($request);
}

// In a blade template
@if(is_super_admin(auth()->user()))
    <div>Super Admin Panel</div>
@endif
```

## Media Collections

The configuration also includes predefined media collections:

- **Avatars** - User profile pictures with thumbnail and preview conversions
- **Documents** - PDF and Word documents
- **Images** - General images with thumbnail conversion

## Changing Super Admin Role Name

1. **Update environment**:
```env
CITADEL_SUPER_ADMIN_ROLE="Administrator"
```

2. **Clear config cache** (if in production):
```bash
php artisan config:clear
```

3. **Update existing roles** (if needed):
```bash
php artisan tinker
>>> $role = Spatie\Permission\Models\Role::where('name', 'Super Admin')->first();
>>> $role->update(['name' => 'Administrator']);
```

The `AppServiceProvider` will automatically use the new role name for permission checks.

## Files Created/Modified

- `config/citadel.php` - Main configuration file
- `app/helpers.php` - Helper functions
- `database/seeders/RolesAndPermissionsSeeder.php` - Role and permission seeder
- `app/Providers/AppServiceProvider.php` - Updated to use configurable role
- `.env.citadel.example` - Example environment variables
- `composer.json` - Updated to autoload helpers

This configuration system makes Citadel flexible and easily customizable for different project requirements.
