<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set up Passport for authentication tests
    $this->setUpPassport();
});

describe('Search API', function () {

    test('search endpoint validates authentication', function () {
        $response = $this->getJson('/api/search?q=test');
        $response->assertStatus(401);
    });

    test('search validates required query parameter', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/search');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'The given data was invalid.',
                ],
            ]);
    });

    test('search validates minimum query length', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/search?q=');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ],
            ]);
    });

    test('search validates maximum query length', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $longQuery = str_repeat('a', 256); // 256 characters

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson("/api/search?q={$longQuery}");

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ],
            ]);
    });

    test('search validates limit parameter range', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        // Test limit too high
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/search?q=test&limit=101');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ],
            ]);

        // Test limit too low
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/search?q=test&limit=0');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ],
            ]);
    });

    test('search validates offset parameter is non-negative', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/search?q=test&offset=-1');

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ],
            ]);
    });

    test('unauthenticated user cannot access search endpoint', function () {
        $response = $this->getJson('/api/search?q=test');

        $response->assertStatus(401);
    });

    // Note: The following tests are commented out because they require Laravel Scout
    // to be properly configured with a search driver. In a real application,
    // you would configure Scout with a driver like Meilisearch, Elasticsearch, etc.

    /*
    test('authenticated user can search with valid query', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        // Create some users to search for
        User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
        User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);
        User::factory()->create(['name' => 'Bob Johnson', 'email' => 'bob@example.com']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/search?q=john');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'results' => [
                        '*' => [
                            'type',
                            'id',
                            'name',
                            'email',
                            'created_at'
                        ]
                    ],
                    'query',
                    'limit',
                    'offset',
                    'total'
                ],
                'message'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'query' => 'john',
                    'limit' => 10,
                    'offset' => 0,
                ]
            ]);
    });
    */
});
