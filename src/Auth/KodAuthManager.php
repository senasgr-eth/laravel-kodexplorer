<?php

namespace Senasgr\KodExplorer\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class KodAuthManager
{
    protected $config;
    protected $mode; // 'standalone' or 'laravel'

    public function __construct()
    {
        $this->config = Config::get('kodexplorer');
        $this->mode = $this->config['auth_mode'] ?? 'laravel';
    }

    /**
     * Handle KodExplorer authentication
     */
    public function handle($request, \Closure $next)
    {
        if ($this->mode === 'standalone') {
            return $this->handleStandaloneAuth($request, $next);
        }

        return $this->handleLaravelAuth($request, $next);
    }

    /**
     * Handle Laravel integrated authentication
     */
    protected function handleLaravelAuth($request, $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has access to KodExplorer
        if (!$this->hasKodExplorerAccess($user)) {
            abort(403, 'Unauthorized access to KodExplorer');
        }

        $this->syncLaravelUser($user);
        return $next($request);
    }

    /**
     * Check if user has access to KodExplorer
     */
    protected function hasKodExplorerAccess($user)
    {
        // Admin always has access
        if ($user->is_admin) {
            return true;
        }

        // Check additional permissions if configured
        if (isset($this->config['user_mapping']['allowed_roles'])) {
            $userRoles = method_exists($user, 'getRoles') ? $user->getRoles() : [];
            $allowedRoles = $this->config['user_mapping']['allowed_roles'];
            return !empty(array_intersect($userRoles, $allowedRoles));
        }

        // By default, all authenticated users have access
        return true;
    }

    /**
     * Handle original KodExplorer authentication
     */
    protected function handleStandaloneAuth($request, $next)
    {
        @session_start();
        if (!isset($_SESSION['kodLogin']) || $_SESSION['kodLogin'] !== true) {
            if ($this->checkCookieAuth()) {
                return $next($request);
            }
            return $this->redirectToLogin();
        }
        @session_write_close();
        return $next($request);
    }

    /**
     * Sync Laravel user to KodExplorer session
     */
    protected function syncLaravelUser($laravelUser)
    {
        $userPath = $this->generateUserPath($laravelUser);
        $this->ensureUserDirectory($userPath);

        $kodUser = [
            'userID' => $laravelUser->id,
            'name' => $laravelUser->name,
            'nickname' => $laravelUser->name,
            'email' => $laravelUser->email,
            'path' => $userPath,
            'role' => $this->mapLaravelRole($laravelUser),
            'status' => 1,
            'config' => $this->getUserConfig($laravelUser)
        ];

        $this->setKodSession($kodUser);
    }

    /**
     * Ensure user directory exists
     */
    protected function ensureUserDirectory($userPath)
    {
        $fullPath = storage_path('app/kodexplorer/users/' . $userPath);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
            mkdir($fullPath . '/data', 0755, true);
            mkdir($fullPath . '/recycle_kod', 0755, true);
        }
    }

    /**
     * Get user configuration
     */
    protected function getUserConfig($user)
    {
        $configPath = storage_path('app/kodexplorer/users/' . $this->generateUserPath($user) . '/data/config.php');
        if (file_exists($configPath)) {
            return include $configPath;
        }
        return $this->getDefaultUserConfig();
    }

    /**
     * Get default user configuration
     */
    protected function getDefaultUserConfig()
    {
        return [
            'theme' => 'default',
            'language' => 'en',
            'fileViewMode' => 'list',
            'dateFormat' => 'Y/m/d H:i',
        ];
    }

    /**
     * Set KodExplorer session
     */
    protected function setKodSession($user)
    {
        @session_start();
        $_SESSION['kodLogin'] = true;
        $_SESSION['kodUser'] = $user;
        $_SESSION['X-CSRF-TOKEN'] = $this->generateCsrfToken();
        @session_write_close();
    }

    /**
     * Generate CSRF token
     */
    protected function generateCsrfToken()
    {
        return bin2hex(random_bytes(10));
    }

    /**
     * Map Laravel user role to KodExplorer role
     */
    protected function mapLaravelRole($user)
    {
        if ($user->is_admin) {
            return '1'; // Admin
        }

        // Check for custom role mapping if configured
        if (isset($this->config['user_mapping']['role_map']) && method_exists($user, 'getRoles')) {
            $userRoles = $user->getRoles();
            $roleMap = $this->config['user_mapping']['role_map'];
            
            foreach ($userRoles as $role) {
                if (isset($roleMap[$role])) {
                    return $roleMap[$role];
                }
            }
        }

        return '2'; // Default user role
    }

    /**
     * Generate user path for KodExplorer
     */
    protected function generateUserPath($user)
    {
        $pathAttribute = $this->config['user_mapping']['path_attribute'] ?? 'name';
        $value = $user->{$pathAttribute} ?? $user->id;
        return strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $value));
    }
}
}
