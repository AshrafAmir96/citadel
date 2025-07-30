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

## 🏷️ Role Management Command

The `citadel:get-role` command provides comprehensive role information including permissions, user counts, and detailed statistics.

### Command Signature

```bash
php artisan citadel:get-role [options]
```

### Options

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `--format` | string | table | Output format: table, json, or plain |
| `--with-permissions` | flag | false | Include permissions for each role |
| `--with-users` | flag | false | Include user count for each role |
| `--role` | string | null | Filter by specific role name |
| `--guard` | string | api | Filter by guard (uses permission_guard() helper) |

### Usage Examples

#### Basic Usage
```bash
# Show all roles in table format
php artisan citadel:get-role

# Show roles with user counts
php artisan citadel:get-role --with-users

# Show roles with permissions
php artisan citadel:get-role --with-permissions

# Show complete information
php artisan citadel:get-role --with-users --with-permissions
```

#### Filtering Options
```bash
# Filter by specific role
php artisan citadel:get-role --role="Super Admin"

# Filter by guard
php artisan citadel:get-role --guard=web

# Combine filters
php artisan citadel:get-role --role="Admin" --guard=api --with-permissions
```

#### Output Formats
```bash
# Table format (default) - beautiful formatted table
php artisan citadel:get-role --format=table

# JSON format - for API integration or scripting
php artisan citadel:get-role --format=json --with-permissions

# Plain text format - for logs or simple output
php artisan citadel:get-role --format=plain --with-users
```

#### Docker Environment
```bash
# Interactive mode
docker-compose exec app php artisan citadel:get-role --with-permissions

# Complete information
docker-compose exec app php artisan citadel:get-role --with-users --with-permissions
```

### Command Features

#### 🎨 Beautiful Table Output
- **Role Icons**: Visual icons for different role types (👑 Super Admin, 🛡️ Admin, etc.)
- **Color Coding**: Important information highlighted with colors
- **Clean Formatting**: Professional table layout with proper alignment
- **Statistics Summary**: Total counts and usage analytics

#### 📊 Comprehensive Statistics
- **Role Analytics**: Total roles, users, and permissions
- **Usage Metrics**: Most and least used roles
- **Permission Breakdown**: Grouped by categories
- **User Distribution**: Users per role analysis

#### 🔍 Detailed Information
- **Permission Grouping**: Permissions categorized by type (users.*, posts.*, etc.)
- **User Lists**: Email addresses of users with each role (in JSON/plain formats)
- **Creation Dates**: When roles were created
- **Guard Information**: Which guard each role belongs to

### Sample Output

#### Table Format
```bash
🏰 Citadel Role Management System
═══════════════════════════════════

📋 Guard: api
📊 Total Roles: 3

+----+------------------+-------------+-------------+--------------------+
| ID | Role Name        | Created     | Users Count | Permissions Count  |
+----+------------------+-------------+-------------+--------------------+
| 1  | 👑 Super Admin   | Jul 29, 2025| 2           | 45                 |
| 2  | 🛡️ Admin         | Jul 29, 2025| 5           | 23                 |
| 3  | 👤 User          | Jul 29, 2025| 150         | 8                  |
+----+------------------+-------------+-------------+--------------------+

🔐 Detailed Permissions
────────────────────────
📋 Super Admin (45 permissions):
  📁 users:
    • users.create
    • users.read
    • users.update
    • users.delete
  📁 posts:
    • posts.create
    • posts.publish
    • posts.moderate

📊 Role Statistics
─────────────────
• Total roles: 3
• Total users across all roles: 157
• Total permissions across all roles: 76
• Roles with users: 3
• Roles with permissions: 3
• Most used role: User (150 users)
• Least used role: Super Admin (2 users)
```

#### JSON Format
```json
[
  {
    "id": 1,
    "name": "Super Admin",
    "guard_name": "api",
    "created_at": "2025-07-29T10:30:00.000000Z",
    "updated_at": "2025-07-29T10:30:00.000000Z",
    "users_count": 2,
    "users": ["admin@example.com", "superadmin@example.com"],
    "permissions_count": 45,
    "permissions": ["users.create", "users.read", "users.update", "..."]
  }
]
```

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
- `citadel:permissions:create` - Create new permissions
- `citadel:backup:create` - Create system backups
- `citadel:user:export` - Export user data
- `citadel:role:create` - Create new roles interactively

## 📋 Command Index

### Available Commands
- [`citadel:create-super-admin`](#-super-admin-creation-command) - Create super administrator users
- [`citadel:get-role`](#️-role-management-command) - Display roles with permissions and statistics

### Quick Reference
```bash
# List all citadel commands
php artisan list citadel

# Get help for specific command  
php artisan citadel:create-super-admin --help
php artisan citadel:get-role --help
```
