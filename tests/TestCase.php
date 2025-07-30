<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Use Passport's testing helpers - point to the correct key location
        Passport::loadKeysFrom(__DIR__.'/../storage');
    }

    protected function setUpPassport(): void
    {
        // Check if oauth_clients table exists before trying to create a client
        try {
            $this->artisan('passport:client', [
                '--personal' => true,
                '--name' => 'Test Personal Access Client',
                '--provider' => 'users',
            ]);
        } catch (\Exception $e) {
            // If passport tables don't exist, skip client creation
            // This handles cases where migrations haven't run yet
            return;
        }
    }
}
