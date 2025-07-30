<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up Passport for authentication tests
    $this->setUpPassport();
    
    // Create permissions and roles for testing
    createMediaPermissionsAndRoles();
    
    // Fake the storage for testing
    Storage::fake('public');
    
    // Set the default guard for Spatie permissions
    config(['auth.defaults.guard' => 'api']);
});

describe('Media Management API', function () {
    
    test('authenticated user can upload file', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.create');
        $token = $user->createToken('Test Token')->accessToken;
        
        $file = UploadedFile::fake()->image('test-image.jpg', 1000, 1000)->size(1024); // 1MB
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/media', [
            'file' => $file,
            'collection' => 'uploads'
        ]);
        
        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'file_name',
                    'mime_type',
                    'size',
                    'collection_name',
                    'url',
                    'created_at'
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'test-image.jpg',
                    'mime_type' => 'image/jpeg',
                    'collection_name' => 'uploads'
                ]
            ]);
            
        // Verify the file was stored (check response contains URL)
        expect($response->json('data.url'))->toContain('test-image.jpg');
    });
    
    test('user can upload file to default collection', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.create');
        $token = $user->createToken('Test Token')->accessToken;
        
        $file = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/media', [
            'file' => $file
        ]);
        
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'document.pdf',
                    'mime_type' => 'application/pdf',
                    'collection_name' => 'default'
                ]
            ]);
    });
    
    test('file upload validates file is required', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.create');
        $token = $user->createToken('Test Token')->accessToken;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/media', []);
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ]
            ]);
    });
    
    test('file upload validates file size limit', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.create');
        $token = $user->createToken('Test Token')->accessToken;
        
        // Create a file larger than the limit (assuming 10MB limit)
        $file = UploadedFile::fake()->create('large-file.pdf', 11000); // 11MB
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/media', [
            'file' => $file
        ]);
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ]
            ]);
    });
    
    test('file upload validates allowed file types', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.create');
        $token = $user->createToken('Test Token')->accessToken;
        
        // Create a potentially dangerous file type
        $file = UploadedFile::fake()->create('malicious.exe', 100);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/media', [
            'file' => $file
        ]);
        
        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ]
            ]);
    });
    
    test('user without permission cannot upload files', function () {
        $user = User::factory()->create();
        // Don't give media.create permission
        $token = $user->createToken('Test Token')->accessToken;
        
        $file = UploadedFile::fake()->image('test.jpg');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/media', [
            'file' => $file
        ]);
        
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                ]
            ]);
    });
    
    test('authenticated user can view their media files', function () {
        $user = User::factory()->create();
        $user->givePermissionTo(['media.view', 'media.create']);
        $token = $user->createToken('Test Token')->accessToken;
        
        // Upload a few files first
        $file1 = UploadedFile::fake()->image('image1.jpg');
        $file2 = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');
        
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->postJson('/api/media', ['file' => $file1]);
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->postJson('/api/media', ['file' => $file2]);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/media');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'file_name',
                        'mime_type',
                        'size',
                        'collection_name',
                        'url',
                        'created_at'
                    ]
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
            ]);
            
        // Should have 2 media files
        expect($response->json('data'))->toHaveCount(2);
    });
    
    test('user can filter media files by collection', function () {
        $user = User::factory()->create();
        $user->givePermissionTo(['media.view', 'media.create']);
        $token = $user->createToken('Test Token')->accessToken;
        
        // Upload files to different collections
        $file1 = UploadedFile::fake()->image('avatar.jpg');
        $file2 = UploadedFile::fake()->image('banner.jpg');
        
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->postJson('/api/media', ['file' => $file1, 'collection' => 'avatars']);
        $this->withHeaders(['Authorization' => 'Bearer ' . $token])
             ->postJson('/api/media', ['file' => $file2, 'collection' => 'banners']);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/media?collection=avatars');
        
        $response->assertStatus(200);
        
        $mediaFiles = $response->json('data');
        expect($mediaFiles)->toHaveCount(1);
        expect($mediaFiles[0]['collection_name'])->toBe('avatars');
    });
    
    test('user can delete their own media file', function () {
        $user = User::factory()->create();
        $user->givePermissionTo(['media.create', 'media.delete']);
        $token = $user->createToken('Test Token')->accessToken;
        
        // First upload a file
        $file = UploadedFile::fake()->image('to-delete.jpg');
        
        $uploadResponse = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                              ->postJson('/api/media', ['file' => $file]);
        
        $mediaId = $uploadResponse->json('data.id');
        
        // Now delete it
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/media/{$mediaId}");
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Media file deleted successfully'
            ]);
    });
    
    test('user cannot delete non-existent media file', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.delete');
        $token = $user->createToken('Test Token')->accessToken;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson('/api/media/99999');
        
        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'MEDIA_NOT_FOUND',
                ]
            ]);
    });
    
    test('user cannot delete media file they do not own', function () {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $user1->givePermissionTo(['media.create', 'media.delete']);
        $user2->givePermissionTo(['media.create', 'media.delete']);
        
        $token1 = $user1->createToken('Test Token')->accessToken;
        $token2 = $user2->createToken('Test Token')->accessToken;
        
        // User 1 uploads a file
        $file = UploadedFile::fake()->image('user1-file.jpg');
        $uploadResponse = $this->withHeaders(['Authorization' => 'Bearer ' . $token1])
                              ->postJson('/api/media', ['file' => $file]);
        
        $mediaId = $uploadResponse->json('data.id');
        
        // User 2 tries to delete User 1's file
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
        ])->deleteJson("/api/media/{$mediaId}");
        
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                ]
            ]);
    });
    
    test('user without delete permission cannot delete media files', function () {
        $user = User::factory()->create();
        $user->givePermissionTo('media.create');
        // Don't give media.delete permission
        $token = $user->createToken('Test Token')->accessToken;
        
        // Upload a file first
        $file = UploadedFile::fake()->image('test.jpg');
        $uploadResponse = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                              ->postJson('/api/media', ['file' => $file]);
        
        $mediaId = $uploadResponse->json('data.id');
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->deleteJson("/api/media/{$mediaId}");
        
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'PERMISSION_DENIED',
                ]
            ]);
    });
    
    test('media endpoint supports pagination', function () {
        $user = User::factory()->create();
        $user->givePermissionTo(['media.view', 'media.create']);
        $token = $user->createToken('Test Token')->accessToken;
        
        // Upload multiple files
        for ($i = 1; $i <= 15; $i++) {
            $file = UploadedFile::fake()->image("image{$i}.jpg");
            $this->withHeaders(['Authorization' => 'Bearer ' . $token])
                 ->postJson('/api/media', ['file' => $file]);
        }
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/media?page=1&limit=10');
        
        $response->assertStatus(200);
        
        $mediaFiles = $response->json('data');
        expect(count($mediaFiles))->toBeLessThanOrEqual(10);
    });
    
    test('unauthenticated user cannot access media endpoints', function () {
        $file = UploadedFile::fake()->image('test.jpg');
        
        // Test upload
        $response = $this->postJson('/api/media', ['file' => $file]);
        $response->assertStatus(401);
        
        // Test index
        $response = $this->getJson('/api/media');
        $response->assertStatus(401);
        
        // Test delete
        $response = $this->deleteJson('/api/media/1');
        $response->assertStatus(401);
    });
});

// Helper function to create media permissions and roles
function createMediaPermissionsAndRoles()
{
    // Create permissions
    $permissions = [
        'media.view',
        'media.create',
        'media.delete',
        'users.view',
        'users.edit',
    ];
    
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission, 'guard_name' => 'api']);
    }
    
    // Create roles
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'api']);
    $editorRole = Role::create(['name' => 'editor', 'guard_name' => 'api']);
    
    // Assign permissions to roles
    $adminRole->givePermissionTo($permissions);
    $editorRole->givePermissionTo(['media.view', 'media.create']);
}
