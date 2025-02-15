<?php

namespace Senasgr\KodExplorer\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class PluginIsolationMiddleware
{
    protected $originalGlobals = [];
    protected $originalConstants = [];

    public function handle(Request $request, Closure $next)
    {
        $this->backupGlobalState();
        $this->setupIsolation();

        $response = $next($request);

        $this->restoreGlobalState();

        return $response;
    }

    protected function backupGlobalState()
    {
        // Backup important globals
        $this->originalGlobals = [
            '_SESSION' => $_SESSION ?? [],
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_FILES' => $_FILES,
        ];

        // Backup constants if defined
        $constants = [
            'DATA_PATH',
            'PLUGIN_PATH',
            'STATIC_PATH',
            'USER_PATH',
            'CONFIG_PATH',
        ];

        foreach ($constants as $constant) {
            if (defined($constant)) {
                $this->originalConstants[$constant] = constant($constant);
            }
        }
    }

    protected function setupIsolation()
    {
        $config = config('kodexplorer.plugins');

        // Set up isolated session
        if (!empty($config['session_prefix'])) {
            $this->setupIsolatedSession($config['session_prefix']);
        }

        // Set up safe paths
        if (!empty($config['path_overrides'])) {
            $this->defineIsolatedPaths($config['path_overrides']);
        }

        // Block unsafe features
        if ($config['safe_mode'] ?? true) {
            $this->enableSafeMode();
        }

        // Set up storage isolation
        if ($config['isolation']['use_laravel_storage'] ?? true) {
            $this->setupStorageIsolation();
        }
    }

    protected function setupIsolatedSession($prefix)
    {
        // Create isolated session namespace
        $_SESSION = [];
        foreach (Session::all() as $key => $value) {
            if (strpos($key, $prefix) === 0) {
                $_SESSION[substr($key, strlen($prefix))] = $value;
            }
        }
    }

    protected function defineIsolatedPaths($paths)
    {
        // Define constants with safe paths
        foreach ($paths as $key => $path) {
            $constant = strtoupper($key) . '_PATH';
            if (!defined($constant)) {
                define($constant, $path);
            }
        }
    }

    protected function enableSafeMode()
    {
        // Prevent access to dangerous PHP functions
        $dangerousFunctions = [
            'exec',
            'shell_exec',
            'system',
            'passthru',
            'proc_open',
            'popen',
            'curl_exec',
            'file_get_contents',
            'file_put_contents',
            'fopen',
            'unlink',
            'rmdir',
            'mkdir',
        ];

        foreach ($dangerousFunctions as $function) {
            if (function_exists($function) && !function_exists("original_$function")) {
                runkit_function_rename($function, "original_$function");
                runkit_function_add($function, '', 'throw new \Exception("Function disabled in safe mode");');
            }
        }
    }

    protected function setupStorageIsolation()
    {
        // Override file operations to use Laravel's storage
        if (!function_exists('original_fopen')) {
            runkit_function_rename('fopen', 'original_fopen');
            runkit_function_add('fopen', '$filename, $mode', '
                $storage = Storage::disk("kodexplorer");
                return $storage->readStream($filename);
            ');
        }
    }

    protected function restoreGlobalState()
    {
        // Restore original globals
        foreach ($this->originalGlobals as $key => $value) {
            $GLOBALS[$key] = $value;
        }

        // Restore original constants
        foreach ($this->originalConstants as $constant => $value) {
            if (defined($constant)) {
                runkit_constant_redefine($constant, $value);
            }
        }

        // Restore original functions
        $functions = [
            'fopen',
            'exec',
            'shell_exec',
            'system',
            'passthru',
            'proc_open',
            'popen',
            'curl_exec',
            'file_get_contents',
            'file_put_contents',
            'unlink',
            'rmdir',
            'mkdir',
        ];

        foreach ($functions as $function) {
            if (function_exists("original_$function")) {
                runkit_function_remove($function);
                runkit_function_rename("original_$function", $function);
            }
        }
    }
}
