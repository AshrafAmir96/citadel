<?php

if (! function_exists('citadel_config')) {
    /**
     * Get a citadel configuration value.
     *
     * @param  mixed  $default
     * @return mixed
     */
    function citadel_config(string $key, $default = null)
    {
        return config("citadel.{$key}", $default);
    }
}

if (! function_exists('super_admin_role')) {
    /**
     * Get the configured super admin role name.
     */
    function super_admin_role(): string
    {
        return citadel_config('super_admin_role', 'Super Admin');
    }
}

if (! function_exists('default_user_role')) {
    /**
     * Get the configured default user role name.
     */
    function default_user_role(): string
    {
        return citadel_config('default_user_role', 'User');
    }
}

if (! function_exists('is_super_admin')) {
    /**
     * Check if a user has the super admin role.
     *
     * @param  \App\Models\User|null  $user
     */
    function is_super_admin($user = null): bool
    {
        $user = $user ?? auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasRole(super_admin_role());
}

if (! function_exists('app_version')) {
    /**
     * Get the application version.
     */
    function app_version(): string
    {
        // Try to get from environment first (set during CI/CD)
        if ($version = env('APP_VERSION')) {
            return $version;
        }

        // Try to get from VERSION file (created during build)
        $versionFile = base_path('VERSION');
        if (file_exists($versionFile)) {
            return trim(file_get_contents($versionFile));
        }

        // Try to get from git (development environment)
        if (function_exists('exec')) {
            $gitVersion = null;
            exec('git describe --tags --abbrev=0 2>/dev/null', $output, $returnCode);
            if ($returnCode === 0 && !empty($output)) {
                $gitVersion = trim($output[0]);
            }
            
            if ($gitVersion) {
                // Add commit hash for development
                exec('git rev-parse --short HEAD 2>/dev/null', $commitOutput, $commitReturnCode);
                if ($commitReturnCode === 0 && !empty($commitOutput)) {
                    return $gitVersion . '-dev.' . trim($commitOutput[0]);
                }
                return $gitVersion . '-dev';
            }
        }

        // Fallback to package.json or composer.json version
        $packageJson = base_path('package.json');
        if (file_exists($packageJson)) {
            $package = json_decode(file_get_contents($packageJson), true);
            if (isset($package['version'])) {
                return $package['version'];
            }
        }

        $composerJson = base_path('composer.json');
        if (file_exists($composerJson)) {
            $composer = json_decode(file_get_contents($composerJson), true);
            if (isset($composer['version'])) {
                return $composer['version'];
            }
        }

        // Final fallback
        return '1.0.0-dev';
    }
}
    }
}

if (! function_exists('permission_guard')) {
    /**
     * Get the configured permission guard.
     */
    function permission_guard(): string
    {
        return citadel_config('permission_guard', 'api');
    }
}

if (! function_exists('can_wildcard')) {
    /**
     * Check if a user has permission using wildcard patterns.
     * Supports checking permissions like 'users.*' for any user permission.
     *
     * @param  \App\Models\User|null  $user
     */
    function can_wildcard($user, string $permission): bool
    {
        $user = $user ?? auth()->user();

        if (! $user) {
            return false;
        }

        // Check for super admin first
        if (is_super_admin($user)) {
            return true;
        }

        // Check exact permission first
        if ($user->can($permission)) {
            return true;
        }

        // Check wildcard patterns
        $parts = explode('.', $permission);
        if (count($parts) >= 2) {
            $resource = $parts[0];
            // Check if user has {resource}.* permission
            if ($user->can($resource.'.*')) {
                return true;
            }

            // Check if user has {resource}.manage permission (implies all actions)
            if ($user->can($resource.'.manage')) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('authorize_wildcard')) {
    /**
     * Authorize a user with wildcard permission support, throw exception if not authorized.
     *
     * @param  \App\Models\User|null  $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    function authorize_wildcard(string $permission, $user = null): void
    {
        if (! can_wildcard($user, $permission)) {
            throw new \Illuminate\Auth\Access\AuthorizationException('This action is unauthorized.');
        }
    }
}

if (! function_exists('app_version')) {
    /**
     * Get the application version.
     */
    function app_version(): string
    {
        // Try to get from environment first (set during CI/CD)
        if ($version = env('APP_VERSION')) {
            return $version;
        }

        // Try to get from VERSION file (created during build)
        $versionFile = base_path('VERSION');
        if (file_exists($versionFile)) {
            return trim(file_get_contents($versionFile));
        }

        // Try to get from git (development environment)
        if (function_exists('exec')) {
            $gitVersion = null;
            exec('git describe --tags --abbrev=0 2>/dev/null', $output, $returnCode);
            if ($returnCode === 0 && !empty($output)) {
                $gitVersion = trim($output[0]);
            }
            
            if ($gitVersion) {
                // Add commit hash for development
                exec('git rev-parse --short HEAD 2>/dev/null', $commitOutput, $commitReturnCode);
                if ($commitReturnCode === 0 && !empty($commitOutput)) {
                    return $gitVersion . '-dev.' . trim($commitOutput[0]);
                }
                return $gitVersion . '-dev';
            }
        }

        // Fallback to package.json or composer.json version
        $packageJson = base_path('package.json');
        if (file_exists($packageJson)) {
            $package = json_decode(file_get_contents($packageJson), true);
            if (isset($package['version'])) {
                return $package['version'];
            }
        }

        $composerJson = base_path('composer.json');
        if (file_exists($composerJson)) {
            $composer = json_decode(file_get_contents($composerJson), true);
            if (isset($composer['version'])) {
                return $composer['version'];
            }
        }

        // Final fallback
        return '1.0.0-dev';
    }
}
