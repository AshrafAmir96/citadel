# Citadel Commands Documentation

This document provides comprehensive information about all custom Artisan commands available in the Citadel Laravel boilerplate.

## 🏰 Super Admin Creation Command

The `citadel:create-super-admin` command provides an interactive and user-friendly way to create super administrator users for your Citadel application.

### Command Signature

```bash
php artisan citadel:create-super-admin {--email=} {--password=} {--name=}
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `--email` | string | No* | Email address for the super admin user |
| `--password` | string | No* | Password for the super admin user |
| `--name` | string | No* | Display name for the super admin user |

*Parameters are optional when running in interactive mode, but required for non-interactive usage.

### Usage Examples

#### Interactive Mode (Recommended)
```bash
php artisan citadel:create-super-admin
```

This will prompt you for each required field with validation:
- Email address with uniqueness validation
- Secure password with strength requirements
- Display name for the user
- Confirmation before creating the user

#### Direct Mode with Parameters
```bash
php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"
```

#### Docker Environment
```bash
# Interactive mode
docker-compose exec app php artisan citadel:create-super-admin

# Direct mode
docker-compose exec app php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"

# Non-interactive with piped input
echo "yes" | docker-compose exec app php artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"
```

#### Laravel Sail
```bash
./vendor/bin/sail artisan citadel:create-super-admin \
  --email=admin@yourcompany.com \
  --password=SecurePassword123! \
  --name="Super Admin"
```

### Command Features

#### ✨ Interactive User Interface
- **Beautiful Table Formatting**: Clean, professional output with proper alignment
- **Confirmation Prompts**: Review details before user creation
- **Progress Indicators**: Clear feedback during the creation process
- **Success Messages**: Detailed confirmation with user information

#### 🔒 Security & Validation
- **Email Uniqueness**: Prevents duplicate user creation
- **Password Strength**: Enforces secure password requirements
- **Input Sanitization**: Properly handles and validates all inputs
- **Error Handling**: Clear error messages for validation failures

#### 🎯 Role Management
- **Automatic Role Assignment**: Assigns "Super Admin" role automatically
- **Permission Integration**: Works with Spatie Laravel Permission
- **Guard Configuration**: Uses the configured API guard
- **Role Validation**: Ensures the "Super Admin" role exists before assignment

### Sample Output

#### Successful Creation
```bash
🏰 Citadel Super Admin Creation
================================
+-------+---------------------------+
| Field | Value                     |
+-------+---------------------------+
| Name  | Super Admin               |
| Email | admin@yourcompany.com     |
| Role  | Super Admin               |
| Guard | api                       |
+-------+---------------------------+

 Create super admin with the above details? (yes/no) [no]:
 > yes

✅ Super admin created successfully!
+---------+---------------------+
| Field   | Value               |
+---------+---------------------+
| ID      | 1                   |
| Name    | Super Admin         |
| Email   | admin@yourcompany.com |
| Role    | Super Admin         |
| Created | 2025-07-29 09:15:05 |
+---------+---------------------+

🎯 Next steps:
   • User can now login with the provided credentials
   • Super admin has access to all system permissions
   • Consider running: php artisan passport:client --personal
```

#### Validation Error
```bash
🏰 Citadel Super Admin Creation
================================
❌ Validation failed:
   • The email has already been taken.
```

### Prerequisites

Before using this command, ensure that:

1. **Database is migrated**: Run `php artisan migrate` 
2. **Roles are seeded**: The "Super Admin" role must exist in the database
3. **Spatie Permission**: The package should be properly configured
4. **Database connection**: Ensure your database is accessible

### Troubleshooting

#### Common Issues

**1. "Super Admin role not found"**
```bash
# Ensure roles are seeded
php artisan db:seed --class=RolesAndPermissionsSeeder
```

**2. "Database connection error"**
```bash
# Check your database configuration
php artisan config:cache
php artisan migrate:status
```

**3. "Permission denied" (Docker)**
```bash
# Ensure proper container permissions
docker-compose exec app chown -R www-data:www-data /var/www/html
```

**4. "Meilisearch client error"**
```bash
# If using Laravel Scout with Meilisearch
composer require meilisearch/meilisearch-php

# Or disable Scout temporarily in User model
# Comment out the Searchable trait
```

### Integration with CI/CD

You can use this command in your deployment scripts:

```bash
# In your deployment script
php artisan citadel:create-super-admin \
  --email=${ADMIN_EMAIL} \
  --password=${ADMIN_PASSWORD} \
  --name="System Administrator" \
  --no-interaction
```

### Best Practices

1. **Use Strong Passwords**: Always use complex passwords for super admin accounts
2. **Unique Emails**: Each super admin should have a unique email address
3. **Environment Variables**: Store credentials in environment variables for production
4. **Regular Audits**: Regularly review super admin accounts and their activity
5. **Backup Before Changes**: Always backup your database before running admin commands

### Related Commands

```bash
# List all users with roles
php artisan tinker --execute="App\Models\User::with('roles')->get()"

# View all available citadel commands
php artisan list citadel

# Check user permissions
php artisan permission:show
```

## 🔄 Future Commands

The Citadel boilerplate will include additional commands in future versions:

- `citadel:user:deactivate` - Deactivate user accounts
- `citadel:role:assign` - Assign roles to users
- `citadel:permissions:sync` - Synchronize permissions
- `citadel:backup:create` - Create system backups

Stay tuned for updates!
