<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Super Admin Role Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of the role that will be granted all permissions
    | automatically. Users with this role will bypass all permission checks
    | and have access to all resources and actions in the application.
    |
    */

    'super_admin_role' => env('CITADEL_SUPER_ADMIN_ROLE', 'Super Admin'),

    /*
    |--------------------------------------------------------------------------
    | Default User Role
    |--------------------------------------------------------------------------
    |
    | This value is the default role that will be assigned to new users
    | upon registration. This role should have basic permissions that
    | all users should have by default.
    |
    */

    'default_user_role' => env('CITADEL_DEFAULT_USER_ROLE', 'User'),

    /*
    |--------------------------------------------------------------------------
    | Permission Guard
    |--------------------------------------------------------------------------
    |
    | This value is the guard that will be used for permissions and roles.
    | This should match the guard used for your authentication system.
    |
    */

    'permission_guard' => env('CITADEL_PERMISSION_GUARD', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Media Collections
    |--------------------------------------------------------------------------
    |
    | These are the media collections that can be used throughout the
    | application. You can add more collections as needed for different
    | types of media files.
    |
    */

    'media_collections' => [
        'avatars' => [
            'disk' => 'public',
            'conversions' => [
                'thumb' => [
                    'width' => 150,
                    'height' => 150,
                    'quality' => 90,
                ],
                'preview' => [
                    'width' => 500,
                    'height' => 500,
                    'quality' => 80,
                ],
            ],
        ],
        'documents' => [
            'disk' => 'public',
            'mime_types' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        ],
        'images' => [
            'disk' => 'public',
            'mime_types' => ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
            'conversions' => [
                'thumb' => [
                    'width' => 300,
                    'height' => 300,
                    'quality' => 90,
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Laravel Scout search functionality. These settings
    | control how search is performed across the application.
    |
    */

    'search' => [
        'per_page' => env('CITADEL_SEARCH_PER_PAGE', 15),
        'max_results' => env('CITADEL_SEARCH_MAX_RESULTS', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for API responses and behavior. These settings control
    | how the API behaves and formats responses.
    |
    */

    'api' => [
        'per_page' => env('CITADEL_API_PER_PAGE', 15),
        'max_per_page' => env('CITADEL_API_MAX_PER_PAGE', 100),
        'rate_limit' => env('CITADEL_API_RATE_LIMIT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for authentication behavior including token expiration
    | and refresh token settings.
    |
    */

    'auth' => [
        'token_expiration_hours' => env('CITADEL_TOKEN_EXPIRATION_HOURS', 24),
        'refresh_token_expiration_days' => env('CITADEL_REFRESH_TOKEN_EXPIRATION_DAYS', 30),
        'password_reset_expiration_minutes' => env('CITADEL_PASSWORD_RESET_EXPIRATION_MINUTES', 60),
    ],

];
