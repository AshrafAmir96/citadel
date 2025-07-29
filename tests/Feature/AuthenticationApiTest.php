<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Authentication API', function () {
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
                'message',
            ])
            ->assertJson([
                'success' => true,
                'token_type' => 'Bearer',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    });

    test('user cannot register with invalid data', function () {
        $userData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'error' => [
                    'code',
                    'message',
                    'details',
                ],
            ])
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                ],
            ]);
    });

    test('user can login with valid credentials', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email', 'created_at'],
                'access_token',
                'token_type',
                'message',
            ])
            ->assertJson([
                'success' => true,
                'token_type' => 'Bearer',
            ]);
    });

    test('user cannot login with invalid credentials', function () {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'AUTHENTICATION_ERROR',
                ],
            ]);
    });

    test('authenticated user can access user endpoint', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->getJson('/api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => ['id', 'name', 'email'],
                'message',
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
    });

    test('unauthenticated user cannot access protected endpoints', function () {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    });

    test('user can logout', function () {
        $user = User::factory()->create();
        $token = $user->createToken('Test Token')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token,
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    });
});
