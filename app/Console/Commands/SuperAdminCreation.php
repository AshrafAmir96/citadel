<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class SuperAdminCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citadel:create-super-admin 
                            {--name= : The name of the super admin}
                            {--email= : The email of the super admin}
                            {--password= : The password of the super admin}
                            {--force : Force creation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user for the Citadel application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🏰 Citadel Super Admin Creation');
        $this->info('================================');

        // Get super admin role name from configuration
        $superAdminRole = super_admin_role();

        // Check if super admin role exists, create if not
        $role = Role::firstOrCreate(
            ['name' => $superAdminRole, 'guard_name' => permission_guard()],
            ['name' => $superAdminRole, 'guard_name' => permission_guard()]
        );

        // Get user details
        $name = $this->option('name') ?: $this->ask('Enter super admin name');
        $email = $this->option('email') ?: $this->ask('Enter super admin email');
        $password = $this->option('password') ?: $this->secret('Enter super admin password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->error('❌ Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("   • $error");
            }

            return 1;
        }

        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            if ($existingUser->hasRole($superAdminRole)) {
                $this->warn("⚠️  User with email '{$email}' already exists and has super admin role.");

                return 0;
            } else {
                if (! $this->option('force') && ! $this->confirm("User with email '{$email}' exists but is not a super admin. Assign super admin role?")) {
                    $this->info('Operation cancelled.');

                    return 0;
                }

                $existingUser->assignRole($role);
                $this->info("✅ Super admin role assigned to existing user: {$existingUser->name} ({$existingUser->email})");

                return 0;
            }
        }

        // Confirm creation
        if (! $this->option('force')) {
            $this->table(['Field', 'Value'], [
                ['Name', $name],
                ['Email', $email],
                ['Role', $superAdminRole],
                ['Guard', permission_guard()],
            ]);

            if (! $this->confirm('Create super admin with the above details?')) {
                $this->info('Operation cancelled.');

                return 0;
            }
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);

            // Assign super admin role
            $user->assignRole($role);

            $this->info('');
            $this->info('✅ Super admin created successfully!');
            $this->info('');
            $this->table(['Field', 'Value'], [
                ['ID', $user->id],
                ['Name', $user->name],
                ['Email', $user->email],
                ['Role', $superAdminRole],
                ['Created', $user->created_at->format('Y-m-d H:i:s')],
            ]);

            $this->info('');
            $this->info('🎯 Next steps:');
            $this->info('   • User can now login with the provided credentials');
            $this->info('   • Super admin has access to all system permissions');
            $this->info('   • Consider running: php artisan passport:client --personal');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Failed to create super admin:');
            $this->error('   '.$e->getMessage());

            return 1;
        }
    }
}
