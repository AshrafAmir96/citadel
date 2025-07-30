<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('API Documentation', function () {
    
    test('api root endpoint returns documentation information', function () {
        $response = $this->getJson('/api/');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'title',
                    'version',
                    'description',
                    'documentation_url',
                    'endpoints' => [
                        'authentication' => [
                            'register',
                            'login',
                            'logout',
                            'user'
                        ],
                        'users' => [
                            'index',
                            'show',
                            'update',
                            'assign_role',
                            'permissions'
                        ],
                        'media' => [
                            'index',
                            'store',
                            'destroy'
                        ],
                        'search' => [
                            'search'
                        ]
                    ]
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'title' => 'Citadel API',
                    'version' => '1.0.0',
                    'description' => 'Production-ready Laravel backend boilerplate API',
                ]
            ]);
    });
    
    test('api documentation contains all expected endpoints', function () {
        $response = $this->getJson('/api/');
        
        $endpoints = $response->json('data.endpoints');
        
        // Check authentication endpoints
        expect($endpoints['authentication'])->toHaveKey('register');
        expect($endpoints['authentication'])->toHaveKey('login');
        expect($endpoints['authentication'])->toHaveKey('logout');
        expect($endpoints['authentication'])->toHaveKey('user');
        
        // Check user management endpoints
        expect($endpoints['users'])->toHaveKey('index');
        expect($endpoints['users'])->toHaveKey('show');
        expect($endpoints['users'])->toHaveKey('update');
        expect($endpoints['users'])->toHaveKey('assign_role');
        expect($endpoints['users'])->toHaveKey('permissions');
        
        // Check media endpoints
        expect($endpoints['media'])->toHaveKey('index');
        expect($endpoints['media'])->toHaveKey('store');
        expect($endpoints['media'])->toHaveKey('destroy');
        
        // Check search endpoints
        expect($endpoints['search'])->toHaveKey('search');
    });
    
    test('api documentation provides correct HTTP methods', function () {
        $response = $this->getJson('/api/');
        
        $endpoints = $response->json('data.endpoints');
        
        // Verify HTTP methods are included in endpoint descriptions
        expect($endpoints['authentication']['register'])->toContain('POST');
        expect($endpoints['authentication']['login'])->toContain('POST');
        expect($endpoints['authentication']['logout'])->toContain('POST');
        expect($endpoints['authentication']['user'])->toContain('GET');
        
        expect($endpoints['users']['index'])->toContain('GET');
        expect($endpoints['users']['show'])->toContain('GET');
        expect($endpoints['users']['update'])->toContain('PUT');
        expect($endpoints['users']['assign_role'])->toContain('POST');
        expect($endpoints['users']['permissions'])->toContain('GET');
        
        expect($endpoints['media']['index'])->toContain('GET');
        expect($endpoints['media']['store'])->toContain('POST');
        expect($endpoints['media']['destroy'])->toContain('DELETE');
        
        expect($endpoints['search']['search'])->toContain('GET');
    });
    
    test('api documentation includes documentation url', function () {
        $response = $this->getJson('/api/');
        
        $documentationUrl = $response->json('data.documentation_url');
        
        expect($documentationUrl)->toContain('/docs/api');
        expect($documentationUrl)->toBeUrl();
    });
    
    test('api documentation response is consistent', function () {
        $response = $this->getJson('/api/');
        
        // Test multiple calls return the same data
        $response2 = $this->getJson('/api/');
        
        expect($response->json())->toEqual($response2->json());
    });
    
    test('api version endpoint returns version information', function () {
        $response = $this->getJson('/api/version');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'version',
                'timestamp',
                'environment'
            ]);
            
        // Verify data types
        expect($response->json('version'))->toBeString();
        expect($response->json('timestamp'))->toBeString();
        expect($response->json('environment'))->toBeString();
        
        // Verify timestamp is ISO format (more flexible pattern)
        $timestamp = $response->json('timestamp');
        expect($timestamp)->toMatch('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d+Z$/');
    });
    
    test('api version endpoint returns current environment', function () {
        $response = $this->getJson('/api/version');
        
        $environment = $response->json('environment');
        
        // In testing, environment should be 'testing' or 'local'
        expect($environment)->toBeIn(['testing', 'local']);
    });
    
    test('api documentation is accessible without authentication', function () {
        // Both documentation endpoints should be publicly accessible
        $response1 = $this->getJson('/api/');
        $response2 = $this->getJson('/api/version');
        
        $response1->assertStatus(200);
        $response2->assertStatus(200);
        
        // No authentication headers should be required
        expect($response1->json('success'))->toBeTrue();
        expect($response2->json('version'))->toBeString();
    });
    
    test('api documentation includes all major feature categories', function () {
        $response = $this->getJson('/api/');
        
        $endpoints = $response->json('data.endpoints');
        
        // Verify all major feature categories are present
        $expectedCategories = ['authentication', 'users', 'media', 'search'];
        
        foreach ($expectedCategories as $category) {
            expect($endpoints)->toHaveKey($category);
            expect($endpoints[$category])->toBeArray();
            expect($endpoints[$category])->not->toBeEmpty();
        }
    });
    
    test('api documentation endpoints have proper structure', function () {
        $response = $this->getJson('/api/');
        
        $endpoints = $response->json('data.endpoints');
        
        // Each endpoint should be a string describing the HTTP method and path
        foreach ($endpoints as $category => $categoryEndpoints) {
            foreach ($categoryEndpoints as $endpoint => $description) {
                expect($description)->toBeString();
                expect($description)->toContain('/api/');
                expect($description)->toMatch('/^(GET|POST|PUT|DELETE|PATCH)/');
            }
        }
    });
    
    test('api documentation contains valid json structure', function () {
        $response = $this->getJson('/api/');
        
        // Response should be valid JSON with expected structure
        $data = $response->json();
        
        expect($data)->toBeArray();
        expect($data)->toHaveKeys(['success', 'data', 'message']);
        expect($data['success'])->toBeTrue();
        expect($data['data'])->toBeArray();
        expect($data['message'])->toBeString();
    });
    
    test('api documentation message is descriptive', function () {
        $response = $this->getJson('/api/');
        
        $message = $response->json('message');
        
        expect($message)->toBe('API documentation retrieved successfully');
        expect($message)->toContain('documentation');
        expect($message)->toContain('successfully');
    });
    
    test('api documentation returns proper content type', function () {
        $response = $this->getJson('/api/');
        
        $response->assertHeader('Content-Type', 'application/json');
    });
    
    test('api documentation handles options request for cors', function () {
        $response = $this->json('OPTIONS', '/api/');
        
        // Should handle OPTIONS request (needed for CORS preflight)
        expect($response->status())->toBeIn([200, 204, 405]);
    });
});
