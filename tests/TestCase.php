<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        // Ensure we have a fresh database with migrations run
        try {
            // Check if the table exists
            if (! Schema::hasTable('oauth_clients')) {
                // Run migrations if the table doesn't exist
                $this->artisan('migrate', ['--force' => true]);
            }

            // Check if a personal access client already exists
            // Note: The newer Passport schema uses 'grant_types' column instead of separate boolean columns
            $existingClient = DB::table('oauth_clients')
                ->where('grant_types', 'like', '%personal_access%')
                ->where('provider', 'users')
                ->first();

            if (! $existingClient) {
                // Create the client using the artisan command
                $this->artisan('passport:client', [
                    '--personal' => true,
                    '--name' => 'Test Personal Access Client',
                    '--provider' => 'users',
                ])->assertExitCode(0);
            }
        } catch (\Exception $e) {
            // If there's any error, try to provide more details
            throw new \Exception('Failed to set up Passport: '.$e->getMessage(), 0, $e);
        }
    }
}
