<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Get role names from config
        $superAdminRole = config('citadel.super_admin_role', 'Super Admin');
        $defaultUserRole = config('citadel.default_user_role', 'User');
        $guard = config('citadel.permission_guard', 'api');

        // Create permissions using dot notation for wildcard support
        $permissions = [
            // User management permissions
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.manage',
            'users.*', // Wildcard for all user permissions

            // Role management permissions
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.manage',
            'roles.assign',
            'roles.*', // Wildcard for all role permissions

            // Permission management
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            'permissions.manage',
            'permissions.*', // Wildcard for all permission operations

            // Media management permissions
            'media.view',
            'media.upload',
            'media.update',
            'media.delete',
            'media.manage',
            'media.*', // Wildcard for all media permissions

            // System management permissions
            'system.manage',
            'system.configure',
            'system.backup',
            'system.restore',
            'system.*', // Wildcard for all system permissions

            // Analytics permissions
            'analytics.view',
            'analytics.export',
            'analytics.*', // Wildcard for all analytics permissions

            // API permissions
            'api.access',
            'api.admin',
            'api.*', // Wildcard for all API permissions
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => $guard,
            ]);
        }

        // Create roles
        $superAdmin = Role::firstOrCreate([
            'name' => $superAdminRole,
            'guard_name' => $guard,
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => $guard,
        ]);

        $moderator = Role::firstOrCreate([
            'name' => 'Moderator',
            'guard_name' => $guard,
        ]);

        $user = Role::firstOrCreate([
            'name' => $defaultUserRole,
            'guard_name' => $guard,
        ]);

        // Assign permissions to roles
        // Super Admin gets all permissions (handled by Gate in AppServiceProvider)

        // Admin gets wildcard permissions for most resources except system
        $admin->syncPermissions([
            'users.*',        // All user permissions
            'roles.*',        // All role permissions
            'permissions.*',  // All permission operations
            'media.*',        // All media permissions
            'analytics.*',    // All analytics permissions
            'api.*',          // All API permissions
        ]);

        // Moderator gets specific permissions for user and media management
        $moderator->syncPermissions([
            'users.view',
            'users.update',
            'roles.view',
            'media.*',        // All media permissions
            'analytics.view',
            'api.access',
        ]);

        // User gets basic permissions with wildcard for their own media
        $user->syncPermissions([
            'users.view',     // Can view users (limited by business logic)
            'media.view',     // Can view media
            'media.upload',   // Can upload media
            'api.access',     // Can access API
        ]);

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info("Super Admin role: {$superAdminRole}");
        $this->command->info("Default User role: {$defaultUserRole}");
    }
}
