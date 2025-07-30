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
    createPermissionsAndRoles();
    
    // Set the default guard for Spatie permissions
    config(['auth.defaults.guard' => 'api']);
});

describe('User Management API', function () {
    
    test('admin can view all users with pagination', function () {
        // Create admin user with permissions
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        // Create some test users
        User::factory()->count(15)->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?page_size=10');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'current_page',
                    'data' => [
                        '*' => ['id', 'name', 'email', 'created_at']
                    ],
                    'per_page',
                    'total',
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'per_page' => 10,
                ]
            ]);
    });
    
    test('admin can filter users by name', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        // Create users with specific names
        User::factory()->create(['name' => 'John Doe']);
        User::factory()->create(['name' => 'Jane Smith']);
        User::factory()->create(['name' => 'Bob Wilson']);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?filter[name]=John');
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
            
        // Check that only John Doe is returned
        $users = $response->json('data.data');
        expect($users)->toHaveCount(1);
        expect($users[0]['name'])->toBe('John Doe');
    });
    
    test('admin can sort users by creation date', function () {
        $admin = User::factory()->create(['created_at' => now()->subDays(3)]);
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        // Create users with different timestamps (all after admin creation)
        $user1 = User::factory()->create(['created_at' => now()->subDays(2)]);
        $user2 = User::factory()->create(['created_at' => now()->subDays(1)]);
        $user3 = User::factory()->create(['created_at' => now()]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?sort=-created_at');
        
        $response->assertStatus(200);
        
        $users = $response->json('data.data');
        // First user should be the most recently created
        expect($users[0]['id'])->toBe($user3->id);
    });
    
    test('admin can include user roles and permissions', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        // Create a user with a role
        $user = User::factory()->create();
        $user->assignRole('editor');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users?include=roles,permissions');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'data' => [
                        '*' => [
                            'id', 'name', 'email',
                            'roles' => [
                                '*' => ['id', 'name', 'guard_name']
                            ]
                        ]
                    ]
                ]
            ]);
    });
    
    test('regular user cannot view all users', function () {
        $user = User::factory()->create();
        // Don't assign admin role
        $token = $user->createToken('Test Token')->accessToken;
        
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
    
    test('admin can view specific user by ID', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $targetUser = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/users/{$targetUser->id}");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email', 'created_at'],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $targetUser->id,
                    'email' => $targetUser->email,
                ]
            ]);
    });
    
    test('admin can view user with roles and permissions included', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $targetUser = User::factory()->create();
        $targetUser->assignRole('editor');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/users/{$targetUser->id}?include=roles,permissions");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id', 'name', 'email',
                    'roles' => [
                        '*' => ['id', 'name', 'guard_name']
                    ]
                ]
            ]);
    });
    
    test('returns 404 for non-existent user', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/users/99999');
        
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                ]
            ]);
    });
    
    test('admin can update user profile', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $targetUser = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com'
        ]);
        
        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/users/{$targetUser->id}", $updateData);
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $targetUser->id,
                    'name' => 'Updated Name',
                    'email' => 'updated@example.com',
                ],
                'message' => 'User updated successfully'
            ]);
            
        // Verify database was updated
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    });
    
    test('user update validates email uniqueness', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        $targetUser = User::factory()->create(['email' => 'target@example.com']);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->putJson("/api/users/{$targetUser->id}", [
            'email' => 'existing@example.com'
        ]);
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ]
            ]);
    });
    
    test('admin can assign role to user', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $targetUser = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/users/{$targetUser->id}/roles", [
            'role' => 'editor'
        ]);
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $targetUser->id,
                    ],
                    'roles' => ['editor']
                ],
                'message' => 'Role assigned successfully'
            ]);
            
        // Verify user has the role
        expect($targetUser->fresh()->hasRole('editor'))->toBeTrue();
    });
    
    test('role assignment validates role exists', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $targetUser = User::factory()->create();
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson("/api/users/{$targetUser->id}/roles", [
            'role' => 'non-existent-role'
        ]);
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ]
            ]);
    });
    
    test('admin can view user permissions', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $token = $admin->createToken('Test Token')->accessToken;
        
        $targetUser = User::factory()->create();
        $targetUser->assignRole('editor');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/users/{$targetUser->id}/permissions");
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'roles',
                    'permissions',
                    'direct_permissions'
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'roles' => ['editor']
                ]
            ]);
    });
    
    test('regular user cannot access other users permissions', function () {
        $user = User::factory()->create();
        $targetUser = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/users/{$targetUser->id}/permissions");
        
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                ]
            ]);
    });
    
    test('unauthenticated user cannot access user endpoints', function () {
        $response = $this->getJson('/api/users');
        $response->assertStatus(401);
        
        $response = $this->getJson('/api/users/1');
        $response->assertStatus(401);
        
        $response = $this->putJson('/api/users/1', ['name' => 'Test']);
        $response->assertStatus(401);
    });
});

// Helper function to create permissions and roles
function createPermissionsAndRoles()
{
    // Create permissions that match what the UserController expects
    $permissions = [
        'users.view',
        'users.create', 
        'users.update',  // Changed from 'users.edit' to match controller
        'users.delete',
        'roles.assign',  // Added for role assignment functionality
        'permissions.view',  // Added for viewing user permissions
        'media.view',
        'media.create',
        'media.delete',
    ];
    
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'api']);
    }
    
    // Create roles
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
    $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'api']);
    
    // Assign permissions to roles
    $adminRole->givePermissionTo($permissions);  // Admin gets all permissions
    $editorRole->givePermissionTo(['users.view', 'media.view', 'media.create']);
}
