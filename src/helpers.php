<?php

if (!function_exists('kodexplorer_path')) {
    /**
     * Get the path to the KodExplorer installation.
     *
     * @param  string  $path
     * @return string
     */
    function kodexplorer_path($path = '')
    {
        return rtrim(config('kodexplorer.storage_path'), '/').($path ? '/'.$path : $path);
    }
}

if (!function_exists('kodexplorer_asset')) {
    /**
     * Generate an asset path for KodExplorer.
     *
     * @param  string  $path
     * @return string
     */
    function kodexplorer_asset($path)
    {
        return asset('vendor/kodexplorer/'.ltrim($path, '/'));
    }
}

if (!function_exists('kodexplorer_user_path')) {
    /**
     * Get the storage path for a specific user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $path
     * @return string
     */
    function kodexplorer_user_path($user, $path = '')
    {
        $userPath = $user->{config('kodexplorer.user_mapping.path_attribute', 'name')};
        return kodexplorer_path('users/'.$userPath.($path ? '/'.$path : ''));
    }
}
