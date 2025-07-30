<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class GetRoleList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'citadel:get-role 
                            {--format=table : Output format (table, json, plain)}
                            {--with-permissions : Include permissions for each role}
                            {--with-users : Include user count for each role}
                            {--role= : Filter by specific role name}
                            {--guard= : Filter by guard (default: api)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display all roles with their permissions and statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $guard = $this->option('guard') ?: permission_guard();
        $specificRole = $this->option('role');
        $format = $this->option('format');
        $withPermissions = $this->option('with-permissions');
        $withUsers = $this->option('with-users');

        // Display header
        $this->info('🏰 Citadel Role Management System');
        $this->info('═══════════════════════════════════');

        // Get roles query
        $rolesQuery = Role::where('guard_name', $guard);

        if ($specificRole) {
            $rolesQuery->where('name', $specificRole);
        }

        $roles = $rolesQuery->orderBy('name')->get();

        if ($roles->isEmpty()) {
            if ($specificRole) {
                $this->error("❌ Role '{$specificRole}' not found for guard '{$guard}'");

                return Command::FAILURE;
            } else {
                $this->warn("⚠️  No roles found for guard '{$guard}'");

                return Command::SUCCESS;
            }
        }

        // Format output based on option
        switch ($format) {
            case 'json':
                $this->outputJson($roles, $withPermissions, $withUsers);
                break;
            case 'plain':
                $this->outputPlain($roles, $withPermissions, $withUsers);
                break;
            case 'table':
            default:
                $this->outputTable($roles, $withPermissions, $withUsers, $guard);
                break;
        }

        return Command::SUCCESS;
    }

    /**
     * Output roles in table format
     */
    private function outputTable($roles, $withPermissions, $withUsers, $guard)
    {
        $this->newLine();
        $this->line("📋 Guard: <comment>{$guard}</comment>");
        $this->line("📊 Total Roles: <info>{$roles->count()}</info>");
        $this->newLine();

        // Prepare table headers
        $headers = ['ID', 'Role Name', 'Created'];

        if ($withUsers) {
            $headers[] = 'Users Count';
        }

        if ($withPermissions) {
            $headers[] = 'Permissions Count';
        }

        $rows = [];

        foreach ($roles as $role) {
            $row = [
                $role->id,
                $this->formatRoleName($role->name),
                $role->created_at->format('M j, Y'),
            ];

            if ($withUsers) {
                $userCount = $role->users()->count();
                $row[] = $userCount > 0 ? "<info>{$userCount}</info>" : '<comment>0</comment>';
            }

            if ($withPermissions) {
                $permissionCount = $role->permissions()->count();
                $row[] = $permissionCount > 0 ? "<info>{$permissionCount}</info>" : '<comment>0</comment>';
            }

            $rows[] = $row;
        }

        $this->table($headers, $rows);

        // Show detailed permissions if requested
        if ($withPermissions) {
            $this->newLine();
            $this->showDetailedPermissions($roles);
        }

        // Show role statistics
        $this->showStatistics($roles);
    }

    /**
     * Output roles in JSON format
     */
    private function outputJson($roles, $withPermissions, $withUsers)
    {
        $output = [];

        foreach ($roles as $role) {
            $roleData = [
                'id' => $role->id,
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'created_at' => $role->created_at->toISOString(),
                'updated_at' => $role->updated_at->toISOString(),
            ];

            if ($withUsers) {
                $roleData['users_count'] = $role->users()->count();
                $roleData['users'] = $role->users()->pluck('email')->toArray();
            }

            if ($withPermissions) {
                $permissions = $role->permissions;
                $roleData['permissions_count'] = $permissions->count();
                $roleData['permissions'] = $permissions->pluck('name')->toArray();
            }

            $output[] = $roleData;
        }

        $this->line(json_encode($output, JSON_PRETTY_PRINT));
    }

    /**
     * Output roles in plain text format
     */
    private function outputPlain($roles, $withPermissions, $withUsers)
    {
        foreach ($roles as $role) {
            $this->line("Role: {$role->name}");
            $this->line("  ID: {$role->id}");
            $this->line("  Guard: {$role->guard_name}");
            $this->line("  Created: {$role->created_at->format('M j, Y H:i')}");

            if ($withUsers) {
                $userCount = $role->users()->count();
                $this->line("  Users: {$userCount}");

                if ($userCount > 0) {
                    $users = $role->users()->pluck('email')->take(5);
                    foreach ($users as $email) {
                        $this->line("    - {$email}");
                    }
                    if ($userCount > 5) {
                        $this->line('    ... and '.($userCount - 5).' more');
                    }
                }
            }

            if ($withPermissions) {
                $permissions = $role->permissions;
                $this->line("  Permissions: {$permissions->count()}");

                if ($permissions->count() > 0) {
                    foreach ($permissions->take(10) as $permission) {
                        $this->line("    - {$permission->name}");
                    }
                    if ($permissions->count() > 10) {
                        $this->line('    ... and '.($permissions->count() - 10).' more');
                    }
                }
            }

            $this->newLine();
        }
    }

    /**
     * Show detailed permissions for each role
     */
    private function showDetailedPermissions($roles)
    {
        $this->line('🔐 <comment>Detailed Permissions</comment>');
        $this->line('────────────────────────');

        foreach ($roles as $role) {
            $permissions = $role->permissions;

            if ($permissions->isEmpty()) {
                $this->line("📋 <info>{$role->name}</info>: <comment>No permissions assigned</comment>");

                continue;
            }

            $this->line("📋 <info>{$role->name}</info> ({$permissions->count()} permissions):");

            // Group permissions by category
            $groupedPermissions = $permissions->groupBy(function ($permission) {
                $parts = explode('.', $permission->name);

                return count($parts) > 1 ? $parts[0] : 'general';
            });

            foreach ($groupedPermissions as $category => $categoryPermissions) {
                $this->line("  📁 <comment>{$category}</comment>:");
                foreach ($categoryPermissions as $permission) {
                    $this->line("    • {$permission->name}");
                }
            }

            $this->newLine();
        }
    }

    /**
     * Show role statistics
     */
    private function showStatistics($roles)
    {
        $this->newLine();
        $this->line('📊 <comment>Role Statistics</comment>');
        $this->line('─────────────────');

        $totalUsers = 0;
        $totalPermissions = 0;
        $rolesWithUsers = 0;
        $rolesWithPermissions = 0;

        foreach ($roles as $role) {
            $userCount = $role->users()->count();
            $permissionCount = $role->permissions()->count();

            $totalUsers += $userCount;
            $totalPermissions += $permissionCount;

            if ($userCount > 0) {
                $rolesWithUsers++;
            }
            if ($permissionCount > 0) {
                $rolesWithPermissions++;
            }
        }

        $this->line("• Total roles: <info>{$roles->count()}</info>");
        $this->line("• Total users across all roles: <info>{$totalUsers}</info>");
        $this->line("• Total permissions across all roles: <info>{$totalPermissions}</info>");
        $this->line("• Roles with users: <info>{$rolesWithUsers}</info>");
        $this->line("• Roles with permissions: <info>{$rolesWithPermissions}</info>");

        // Find most and least used roles
        $roleUserCounts = $roles->map(function ($role) {
            return [
                'name' => $role->name,
                'users' => $role->users()->count(),
            ];
        });

        $mostUsed = $roleUserCounts->sortByDesc('users')->first();
        $leastUsed = $roleUserCounts->sortBy('users')->first();

        if ($mostUsed && $mostUsed['users'] > 0) {
            $this->line("• Most used role: <info>{$mostUsed['name']}</info> ({$mostUsed['users']} users)");
        }

        if ($leastUsed) {
            $this->line("• Least used role: <comment>{$leastUsed['name']}</comment> ({$leastUsed['users']} users)");
        }
    }

    /**
     * Format role name with icons
     */
    private function formatRoleName($name)
    {
        $icons = [
            'Super Admin' => '👑',
            'Admin' => '🛡️',
            'Manager' => '👔',
            'User' => '👤',
            'Guest' => '👥',
            'Moderator' => '🔨',
            'Editor' => '✏️',
            'Viewer' => '👁️',
        ];

        $icon = $icons[$name] ?? '🏷️';

        return "{$icon} {$name}";
    }
}
