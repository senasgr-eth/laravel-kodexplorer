<?php

namespace Senasgr\KodExplorer\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PluginHelper
{
    protected static $loadedPlugins = [];

    /**
     * Load a plugin safely
     */
    public static function loadPlugin($pluginName)
    {
        $config = config('kodexplorer.plugins');

        // Check if plugin is allowed
        if (!self::isPluginAllowed($pluginName)) {
            return false;
        }

        // Check if already loaded
        if (isset(self::$loadedPlugins[$pluginName])) {
            return true;
        }

        // Get plugin path
        $pluginPath = self::getPluginPath($pluginName);
        if (!file_exists($pluginPath)) {
            return false;
        }

        try {
            // Load plugin in isolated environment
            self::isolateAndLoad($pluginPath, $pluginName);
            self::$loadedPlugins[$pluginName] = true;
            return true;
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }

    /**
     * Check if plugin is allowed
     */
    protected static function isPluginAllowed($pluginName)
    {
        $config = config('kodexplorer.plugins');

        // Check blacklist
        if (in_array($pluginName, $config['blacklist'] ?? [])) {
            return false;
        }

        // Check whitelist if enabled
        if (!empty($config['whitelist'])) {
            return in_array($pluginName, $config['whitelist']);
        }

        return true;
    }

    /**
     * Get plugin path
     */
    protected static function getPluginPath($pluginName)
    {
        $basePath = config('kodexplorer.plugins.storage_path');
        return $basePath . '/' . $pluginName . '/app.php';
    }

    /**
     * Load plugin in isolated environment
     */
    protected static function isolateAndLoad($pluginPath, $pluginName)
    {
        // Create isolated storage context
        $storage = Storage::build([
            'driver' => 'local',
            'root' => dirname($pluginPath),
        ]);

        // Create isolated session namespace
        $sessionPrefix = config('kodexplorer.plugins.session_prefix', 'kodexplorer_plugin_');
        $session = new class($sessionPrefix . $pluginName) {
            protected $prefix;
            
            public function __construct($prefix)
            {
                $this->prefix = $prefix;
            }
            
            public function get($key, $default = null)
            {
                return Session::get($this->prefix . $key, $default);
            }
            
            public function put($key, $value)
            {
                Session::put($this->prefix . $key, $value);
            }
        };

        // Define safe constants
        $constants = [
            'PLUGIN_NAME' => $pluginName,
            'PLUGIN_PATH' => dirname($pluginPath),
            'PLUGIN_URL' => asset('vendor/kodexplorer/plugins/' . $pluginName),
        ];

        foreach ($constants as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        // Load plugin with limited scope
        require $pluginPath;
    }

    /**
     * Get loaded plugins
     */
    public static function getLoadedPlugins()
    {
        return array_keys(self::$loadedPlugins);
    }

    /**
     * Check if plugin is loaded
     */
    public static function isLoaded($pluginName)
    {
        return isset(self::$loadedPlugins[$pluginName]);
    }

    /**
     * Get available plugins
     */
    public static function getAvailablePlugins()
    {
        $config = config('kodexplorer.plugins');
        $basePath = $config['storage_path'];
        
        if (!is_dir($basePath)) {
            return [];
        }

        $plugins = [];
        $directories = array_diff(scandir($basePath), ['.', '..']);

        foreach ($directories as $dir) {
            if (is_dir($basePath . '/' . $dir)) {
                $plugins[] = [
                    'name' => $dir,
                    'loaded' => self::isLoaded($dir),
                    'allowed' => self::isPluginAllowed($dir),
                    'path' => $basePath . '/' . $dir,
                ];
            }
        }

        return $plugins;
    }
}
