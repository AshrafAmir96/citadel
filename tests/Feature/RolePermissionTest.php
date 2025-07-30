<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up Passport for authentication tests
    $this->setUpPassport();
    
    // Create permissions and roles for testing
    createTestPermissionsAndRoles();
    
    // Set the default guard for Spatie permissions
    config(['auth.defaults.guard' => 'api']);
});

describe('Role and Permission System', function () {
    
    test('user can be assigned a role', function () {
        $user = User::factory()->create();
        
        $role = Role::findByName('editor', 'api');
        $user->assignRole($role);
        
        expect($user->hasRole('editor'))->toBeTrue();
        expect($user->roles->pluck('name')->toArray())->toContain('editor');
    });
    
    test('user can be assigned multiple roles', function () {
        $user = User::factory()->create();
        
        $user->assignRole(['editor', 'moderator']);
        
        expect($user->hasRole('editor'))->toBeTrue();
        expect($user->hasRole('moderator'))->toBeTrue();
        expect($user->roles)->toHaveCount(2);
    });
    
    test('user inherits permissions from role', function () {
        $user = User::factory()->create();
        $user->assignRole('editor');
        
        // Editor role should have specific permissions
        expect($user->can('posts.create'))->toBeTrue();
        expect($user->can('posts.edit'))->toBeTrue();
        expect($user->can('posts.view'))->toBeTrue();
        
        // Editor should not have admin permissions
        expect($user->can('users.delete'))->toBeFalse();
    });
    
    test('user can have direct permissions', function () {
        $user = User::factory()->create();
        
        $permission = Permission::findByName('posts.publish', 'api');
        $user->givePermissionTo($permission);
        
        expect($user->can('posts.publish'))->toBeTrue();
        expect($user->getDirectPermissions()->pluck('name')->toArray())->toContain('posts.publish');
    });
    
    test('user can have permissions revoked', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('posts.create');
        
        expect($user->can('posts.create'))->toBeTrue();
        
        $user->revokePermissionTo('posts.create');
        
        expect($user->can('posts.create'))->toBeFalse();
    });
    
    test('user can have role removed', function () {
        $user = User::factory()->create();
        $user->assignRole('editor');
        
        expect($user->hasRole('editor'))->toBeTrue();
        
        $user->removeRole('editor');
        
        expect($user->hasRole('editor'))->toBeFalse();
        expect($user->can('posts.create'))->toBeFalse();
    });
    
    test('admin role has all permissions', function () {
        $user = User::factory()->create();
        $user->assignRole('admin');
        
        // Admin should have all permissions
        expect($user->can('users.create'))->toBeTrue();
        expect($user->can('users.edit'))->toBeTrue();
        expect($user->can('users.delete'))->toBeTrue();
        expect($user->can('posts.create'))->toBeTrue();
        expect($user->can('posts.edit'))->toBeTrue();
        expect($user->can('posts.delete'))->toBeTrue();
        expect($user->can('media.upload'))->toBeTrue();
        expect($user->can('media.delete'))->toBeTrue();
    });
    
    test('role permissions are cumulative', function () {
        $user = User::factory()->create();
        $user->assignRole(['editor', 'moderator']);
        
        // Should have permissions from both roles
        expect($user->can('posts.create'))->toBeTrue(); // From editor
        expect($user->can('posts.edit'))->toBeTrue();   // From editor
        expect($user->can('users.moderate'))->toBeTrue(); // From moderator
        expect($user->can('comments.moderate'))->toBeTrue(); // From moderator
    });
    
    test('direct permissions override role permissions', function () {
        $user = User::factory()->create();
        $user->assignRole('viewer'); // Only has view permissions
        
        // Give direct permission that viewer role doesn't have
        $user->givePermissionTo('posts.create');
        
        expect($user->can('posts.create'))->toBeTrue();
        expect($user->can('posts.edit'))->toBeFalse(); // Still doesn't have this
    });
    
    test('wildcard permissions work correctly', function () {
        // Create a wildcard permission
        Permission::create(['name' => 'posts.*', 'guard_name' => 'api']);
        
        $user = User::factory()->create();
        $user->givePermissionTo('posts.*');
        
        // Should have all posts permissions
        expect($user->can('posts.create'))->toBeTrue();
        expect($user->can('posts.edit'))->toBeTrue();
        expect($user->can('posts.delete'))->toBeTrue();
        expect($user->can('posts.publish'))->toBeTrue();
        
        // Should not have permissions outside posts
        expect($user->can('users.create'))->toBeFalse();
    });
    
    test('permission middleware blocks unauthorized access', function () {
        $user = User::factory()->create();
        // User has no permissions
        $token = $user->createToken('Test Token')->accessToken;
        
        // Try to access admin-only endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');
        
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                ]
            ]);
    });
    
    test('permission middleware allows authorized access', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('users.view');
        $token = $user->createToken('Test Token')->accessToken;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users');
        
        $response->assertStatus(200);
    });
    
    test('role assignment validates role exists', function () {
        $user = User::factory()->create();
        
        $this->expectException(\Spatie\Permission\Exceptions\RoleDoesNotExist::class);
        
        $user->assignRole('non-existent-role');
    });
    
    test('permission assignment validates permission exists', function () {
        $user = User::factory()->create();
        
        $this->expectException(\Spatie\Permission\Exceptions\PermissionDoesNotExist::class);
        
        $user->givePermissionTo('non-existent-permission');
    });
    
    test('user can check specific permissions', function () {
        $user = User::factory()->create();
        $user->givePermissionTo(['posts.create', 'posts.edit']);
        
        expect($user->can('posts.create'))->toBeTrue();
        expect($user->can('posts.edit'))->toBeTrue();
        expect($user->can('posts.delete'))->toBeFalse();
        
        // Check multiple permissions at once
        expect($user->hasAllPermissions(['posts.create', 'posts.edit']))->toBeTrue();
        expect($user->hasAllPermissions(['posts.create', 'posts.delete']))->toBeFalse();
        expect($user->hasAnyPermission(['posts.create', 'posts.delete']))->toBeTrue();
    });
    
    test('permissions are scoped to guard', function () {
        // Create permission for web guard
        Permission::create(['name' => 'web.permission', 'guard_name' => 'web']);
        
        $user = User::factory()->create();
        
        // Should not be able to assign web permission to api user
        $this->expectException(\Spatie\Permission\Exceptions\GuardDoesNotMatch::class);
        
        $user->givePermissionTo('web.permission');
    });
    
    test('user getAllPermissions returns all permissions', function () {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $user->givePermissionTo('posts.publish'); // Direct permission
        
        $allPermissions = $user->getAllPermissions();
        $permissionNames = $allPermissions->pluck('name')->toArray();
        
        // Should include both role permissions and direct permissions
        expect($permissionNames)->toContain('posts.create'); // From role
        expect($permissionNames)->toContain('posts.edit');   // From role
        expect($permissionNames)->toContain('posts.publish'); // Direct permission
    });
    
    test('user getPermissionsViaRoles returns only role permissions', function () {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $user->givePermissionTo('posts.publish'); // Direct permission
        
        $rolePermissions = $user->getPermissionsViaRoles();
        $permissionNames = $rolePermissions->pluck('name')->toArray();
        
        // Should include only role permissions
        expect($permissionNames)->toContain('posts.create'); // From role
        expect($permissionNames)->toContain('posts.edit');   // From role
        expect($permissionNames)->not->toContain('posts.publish'); // Direct permission excluded
    });
    
    test('user getDirectPermissions returns only direct permissions', function () {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $user->givePermissionTo('posts.publish'); // Direct permission
        
        $directPermissions = $user->getDirectPermissions();
        $permissionNames = $directPermissions->pluck('name')->toArray();
        
        // Should include only direct permissions
        expect($permissionNames)->toContain('posts.publish'); // Direct permission
        expect($permissionNames)->not->toContain('posts.create'); // Role permission excluded
        expect($permissionNames)->not->toContain('posts.edit');   // Role permission excluded
    });
});

// Helper function to create test permissions and roles
function createTestPermissionsAndRoles()
{
    // Create permissions
    $permissions = [
        // User permissions
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
        'users.moderate',
        
        // Post permissions
        'posts.view',
        'posts.create',
        'posts.edit',
        'posts.delete',
        'posts.publish',
        
        // Comment permissions
        'comments.view',
        'comments.create',
        'comments.edit',
        'comments.delete',
        'comments.moderate',
        
        // Media permissions
        'media.view',
        'media.upload',
        'media.delete',
    ];
    
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'api']);
    }
    
    // Create roles
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
    $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'api']);
    $moderatorRole = Role::create(['name' => 'moderator', 'guard_name' => 'api']);
    $viewerRole = Role::create(['name' => 'viewer', 'guard_name' => 'api']);
    
    // Assign permissions to roles
    $adminRole->givePermissionTo($permissions); // Admin gets all permissions
    
    $editorRole->givePermissionTo([
        'posts.view',
        'posts.create',
        'posts.edit',
        'media.view',
        'media.upload',
    ]);
    
    $moderatorRole->givePermissionTo([
        'users.moderate',
        'comments.moderate',
        'posts.view',
        'comments.view',
    ]);
    
    $viewerRole->givePermissionTo([
        'posts.view',
        'comments.view',
        'media.view',
    ]);
}
