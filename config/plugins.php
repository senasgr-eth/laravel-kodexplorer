<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Plugin Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring how KodExplorer plugins work with Laravel
    |
    */

    // Plugin storage location (isolated from Laravel)
    'storage_path' => storage_path('app/kodexplorer/plugins'),

    // Session namespace to avoid conflicts
    'session_prefix' => 'kodexplorer_plugin_',

    // Plugin-specific routes prefix
    'route_prefix' => 'kodexplorer/plugins',

    // Safe mode - prevents plugins from accessing Laravel internals
    'safe_mode' => true,

    // Allowed plugin features
    'allowed_features' => [
        'file_operations' => true,  // File read/write
        'ui_customization' => true, // UI modifications
        'preview_handlers' => true,  // File preview
        'editor_plugins' => true,    // Editor extensions
        'database_access' => false,  // No direct DB access
        'system_commands' => false,  // No system commands
        'network_requests' => false, // No external requests
    ],

    // Plugin isolation settings
    'isolation' => [
        // Prevent access to Laravel facades
        'block_facades' => true,
        
        // Prevent global function definitions
        'block_globals' => true,
        
        // Prevent superglobal access
        'block_superglobals' => true,
        
        // Use Laravel's storage instead of direct filesystem
        'use_laravel_storage' => true,
    ],

    // Plugin whitelist (only these plugins will be loaded)
    'whitelist' => [
        'imageExif',     // Image metadata viewer
        'DPlayer',       // Video player
        'VLCPlayer',     // Alternative video player
        'photoSwipe',    // Image gallery
        'webodf',        // Document viewer
        'yzOffice',      // Office viewer
        'zipView',       // Archive viewer
    ],

    // Plugin blacklist (these plugins will never load)
    'blacklist' => [
        'adminer',       // Conflicts with Laravel DB management
        'toolsCommon',   // System-level operations not allowed
    ],

    // Plugin dependencies that are safe to use
    'safe_dependencies' => [
        'jquery' => 'vendor/kodexplorer/lib/jquery.min.js',
        'bootstrap' => 'vendor/kodexplorer/lib/bootstrap.min.js',
        'ace' => 'vendor/kodexplorer/lib/ace/ace.js',
    ],

    // Override plugin paths to use Laravel structure
    'path_overrides' => [
        'data' => storage_path('app/kodexplorer/data'),
        'temp' => storage_path('app/kodexplorer/temp'),
        'cache' => storage_path('app/kodexplorer/cache'),
        'logs' => storage_path('logs/kodexplorer'),
    ],
];
