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
        
        // Set up Passport client after database is refreshed
        $this->setUpPassport();
    }
    
    protected function setUpPassport(): void
    {
        $this->artisan('passport:client', [
            '--personal' => true,
            '--name' => 'Test Personal Access Client',
            '--provider' => 'users',
        ]);
    }
}
