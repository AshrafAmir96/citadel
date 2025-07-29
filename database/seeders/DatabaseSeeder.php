<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolesAndPermissionsSeeder::class);

        // Create a test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Assign default role to test user
        $defaultRole = config('citadel.default_user_role', 'User');
        $testUser->assignRole($defaultRole);

        // Create a super admin user
        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
        ]);

        // Assign super admin role
        $superAdminRole = config('citadel.super_admin_role', 'Super Admin');
        $superAdminUser->assignRole($superAdminRole);
    }
}
