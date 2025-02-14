<?php

return [
    /*
    |--------------------------------------------------------------------------
    | KodExplorer Path
    |--------------------------------------------------------------------------
    |
    | This value determines the path where KodExplorer will be accessible from
    | your web browser. You should include a valid path that is relative to
    | your project's public directory.
    |
    */
    'path' => 'kodexplorer',

    /*
    |--------------------------------------------------------------------------
    | Storage Path
    |--------------------------------------------------------------------------
    |
    | This value determines where your files will be stored. By default, this
    | will be within your storage directory.
    |
    */
    'storage_path' => storage_path('app/kodexplorer'),

    /*
    |--------------------------------------------------------------------------
    | Authentication Mode
    |--------------------------------------------------------------------------
    |
    | This option controls how KodExplorer handles authentication:
    | - 'standalone': Uses KodExplorer's built-in authentication system
    | - 'laravel': Integrates with Laravel's authentication system
    |
    */
    'auth_mode' => env('KODEXPLORER_AUTH_MODE', 'laravel'),

    /*
    |--------------------------------------------------------------------------
    | User Mapping
    |--------------------------------------------------------------------------
    |
    | When using Laravel authentication, these settings determine how Laravel
    | users are mapped to KodExplorer roles and permissions.
    |
    */
    'user_mapping' => [
        // Define which Laravel user attributes map to admin role
        'admin_check' => 'is_admin',  // Laravel user model attribute that determines admin status
        'admin_values' => [true, 1, 'yes'],  // Values that indicate admin status
        
        // Custom path generation for user directories
        'path_attribute' => 'name',  // Which Laravel user attribute to use for path generation

        // Role mapping (optional)
        'role_map' => [
            'super-admin' => '1',    // KodExplorer admin
            'manager' => '2',         // Normal user with extended privileges
            'user' => '3',            // Basic user
        ],

        // Allowed roles (optional)
        'allowed_roles' => ['super-admin', 'manager', 'user'],

        // Default settings for new users
        'default_settings' => [
            'theme' => 'default',
            'language' => 'en',
            'fileViewMode' => 'list',
            'dateFormat' => 'Y/m/d H:i',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    |
    | KodExplorer specific session settings
    |
    */
    'session' => [
        'lifetime' => 120,  // Session lifetime in minutes
        'expire_on_close' => false,
        'cookie_name' => 'kodexplorer_session',
    ],

    /*
    |--------------------------------------------------------------------------
    | Access Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the authentication and authorization settings
    | for KodExplorer.
    |
    */
    'middleware' => ['web', 'kodexplorer.auth'],

    /*
    |--------------------------------------------------------------------------
    | Original KodExplorer Settings
    |--------------------------------------------------------------------------
    |
    | Original KodExplorer configuration settings
    |
    */
    'kod_settings' => [
        'auto_login' => env('KODEXPLORER_AUTO_LOGIN', false),
        'password_check' => env('KODEXPLORER_PASSWORD_CHECK', true),
    ],
];
