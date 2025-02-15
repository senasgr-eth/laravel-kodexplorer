# KodExplorer with SSH Integration Guide

This guide demonstrates how to integrate KodExplorer with phpseclib in a Laravel application to explore remote servers via SSH.

## Prerequisites

- Laravel 8.0+
- PHP 7.3+
- Composer
- phpseclib 3.0+
- laravel-kodexplorer package

## Installation

1. Create a new Laravel project:
```bash
laravel new ssh-explorer
cd ssh-explorer
```

2. Install required packages:
```bash
composer require senasgr-eth/laravel-kodexplorer
composer require phpseclib/phpseclib:~3.0
```

## Implementation

### 1. Create SSH Storage Driver

Create a new file `app/Storage/SSHStorageDriver.php`:

```php
<?php

namespace App\Storage;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SFTP;
use phpseclib3\Crypt\PublicKeyLoader;

class SSHStorageDriver implements Filesystem
{
    protected $sftp;
    protected $connection;

    public function __construct(array $config)
    {
        $this->sftp = new SFTP($config['host'], $config['port'] ?? 22);
        
        if (isset($config['private_key'])) {
            $key = PublicKeyLoader::load(file_get_contents($config['private_key']));
            $this->connection = $this->sftp->login($config['username'], $key);
        } else {
            $this->connection = $this->sftp->login($config['username'], $config['password']);
        }
    }

    public function read($path)
    {
        return $this->sftp->get($path);
    }

    public function write($path, $contents, $options = [])
    {
        return $this->sftp->put($path, $contents);
    }

    public function delete($path)
    {
        return $this->sftp->delete($path);
    }

    public function exists($path)
    {
        return $this->sftp->file_exists($path);
    }

    public function copy($from, $to)
    {
        $content = $this->read($from);
        return $this->write($to, $content);
    }

    public function move($from, $to)
    {
        return $this->sftp->rename($from, $to);
    }

    public function size($path)
    {
        return $this->sftp->size($path);
    }

    public function makeDirectory($path)
    {
        return $this->sftp->mkdir($path, 0755, true);
    }

    public function deleteDirectory($path)
    {
        return $this->sftp->rmdir($path);
    }

    public function files($directory)
    {
        $files = [];
        $list = $this->sftp->nlist($directory);
        
        foreach ($list as $item) {
            if ($this->sftp->is_file($directory . '/' . $item)) {
                $files[] = $item;
            }
        }
        
        return $files;
    }

    public function allFiles($directory)
    {
        // Implement recursive file listing
        return $this->files($directory);
    }

    public function directories($directory)
    {
        $dirs = [];
        $list = $this->sftp->nlist($directory);
        
        foreach ($list as $item) {
            if ($this->sftp->is_dir($directory . '/' . $item)) {
                $dirs[] = $item;
            }
        }
        
        return $dirs;
    }
}
```

### 2. Create Storage Service Provider

Create `app/Providers/SSHStorageServiceProvider.php`:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Storage\SSHStorageDriver;

class SSHStorageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Storage::extend('ssh', function ($app, $config) {
            return new SSHStorageDriver($config);
        });
    }
}
```

### 3. Configure Storage

Update `config/filesystems.php`:

```php
'disks' => [
    // ... other disks ...

    'ssh' => [
        'driver' => 'ssh',
        'host' => env('SSH_HOST'),
        'username' => env('SSH_USERNAME'),
        'password' => env('SSH_PASSWORD'),
        // Or use private key:
        // 'private_key' => storage_path('app/ssh/private_key'),
        'port' => env('SSH_PORT', 22),
    ],
],
```

### 4. Configure KodExplorer

Update `config/kodexplorer.php`:

```php
return [
    // ... other config ...

    'storage' => [
        'driver' => 'ssh',  // Use our SSH driver
        'root' => env('SSH_ROOT_PATH', '/'),
    ],
];
```

### 5. Create SSH Manager Controller

Create `app/Http/Controllers/SSHManagerController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Senasgr\KodExplorer\Facades\KodExplorer;

class SSHManagerController extends Controller
{
    public function connect(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'username' => 'required',
            'password' => 'required_without:private_key',
            'private_key' => 'required_without:password',
        ]);

        session([
            'ssh_connection' => [
                'host' => $request->host,
                'username' => $request->username,
                'password' => $request->password,
                'private_key' => $request->private_key,
            ]
        ]);

        config(['filesystems.disks.ssh' => [
            'driver' => 'ssh',
            'host' => $request->host,
            'username' => $request->username,
            'password' => $request->password,
            'private_key' => $request->private_key,
        ]]);

        return redirect()->route('kodexplorer.index');
    }

    public function disconnect()
    {
        session()->forget('ssh_connection');
        return redirect()->route('home');
    }
}
```

### 6. Create SSH Connection Form

Create `resources/views/ssh/connect.blade.php`:

```php
<form action="{{ route('ssh.connect') }}" method="POST">
    @csrf
    <div>
        <label>Host:</label>
        <input type="text" name="host" required>
    </div>
    <div>
        <label>Username:</label>
        <input type="text" name="username" required>
    </div>
    <div>
        <label>Authentication Method:</label>
        <select name="auth_method" id="auth_method">
            <option value="password">Password</option>
            <option value="key">SSH Key</option>
        </select>
    </div>
    <div id="password_auth">
        <label>Password:</label>
        <input type="password" name="password">
    </div>
    <div id="key_auth" style="display:none;">
        <label>Private Key:</label>
        <textarea name="private_key"></textarea>
    </div>
    <button type="submit">Connect</button>
</form>
```

### 7. Add Routes

Update `routes/web.php`:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('ssh/connect', [SSHManagerController::class, 'showConnectForm'])->name('ssh.connect.form');
    Route::post('ssh/connect', [SSHManagerController::class, 'connect'])->name('ssh.connect');
    Route::post('ssh/disconnect', [SSHManagerController::class, 'disconnect'])->name('ssh.disconnect');
});
```

### 8. Create SSH Connection Middleware

Create `app/Http/Middleware/EnsureSSHConnection.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSSHConnection
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('ssh_connection')) {
            return redirect()->route('ssh.connect.form')
                ->with('error', 'Please connect to SSH first');
        }

        return $next($request);
    }
}
```

## Usage

1. Register the SSHStorageServiceProvider in `config/app.php`:
```php
'providers' => [
    // ...
    App\Providers\SSHStorageServiceProvider::class,
],
```

2. Add middleware to `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ...
    'ssh.connected' => \App\Http\Middleware\EnsureSSHConnection::class,
];
```

3. Update your `.env` file with default SSH settings:
```env
SSH_HOST=your-server.com
SSH_USERNAME=your-username
SSH_PASSWORD=your-password
# Or for SSH key authentication:
# SSH_PRIVATE_KEY=/path/to/private/key
SSH_PORT=22
SSH_ROOT_PATH=/
```

4. Access the SSH connection form at `/ssh/connect`

5. After successful connection, you'll be redirected to KodExplorer which will now operate on the remote server via SSH.

## Security Considerations

1. Store sensitive SSH credentials securely
2. Use SSH key authentication when possible
3. Implement proper user authentication
4. Limit SSH access to authorized users
5. Monitor and log SSH operations
6. Implement rate limiting on SSH connections
7. Use secure SSL/TLS for web interface

## Troubleshooting

1. **Connection Issues**
   - Verify SSH credentials
   - Check server firewall settings
   - Ensure proper SSH key permissions

2. **File Permission Issues**
   - Check SSH user permissions on remote server
   - Verify file ownership
   - Check directory permissions

3. **Performance Issues**
   - Consider implementing caching
   - Optimize file listings
   - Use connection pooling

## Contributing

Please submit issues and pull requests to improve this integration.

## License

This integration guide is licensed under the MIT license.
