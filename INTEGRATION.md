# Laravel KodExplorer Integration Guide

## Prerequisites

- Laravel 8.0+
- PHP 7.3+
- Database with support for Laravel migrations
- Composer

## Installation

1. **Install via Composer**:
```bash
composer require senasgr-eth/laravel-kodexplorer
```

2. **Add Required Database Column**:
```bash
php artisan make:migration add_is_admin_to_users_table
```

Update the migration file:
```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_admin');
    });
}
```

Run the migration:
```bash
php artisan migrate
```

3. **Install KodExplorer**:
```bash
php artisan kodexplorer:install
```

## User Model Setup

Update your User model (`app/Models/User.php`):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    // Optional: If using role-based access
    public function getRoles()
    {
        return $this->roles->pluck('name')->toArray();
    }
}
```

## Configuration

### Basic Configuration

Publish configuration:
```bash
php artisan vendor:publish --provider="Senasgr\\KodExplorer\\KodExplorerServiceProvider" --tag="config"
```

### Role-Based Access (Optional)

If you want to use role-based access, update `config/kodexplorer.php`:

```php
'user_mapping' => [
    // Admin flag configuration
    'admin_check' => 'is_admin',
    'admin_values' => [true, 1, 'yes'],

    // Role mapping
    'role_map' => [
        'super-admin' => '1',    // Full access
        'manager' => '2',        // Extended privileges
        'user' => '3',           // Basic access
    ],

    // Define which roles can access KodExplorer
    'allowed_roles' => ['super-admin', 'manager', 'user'],

    // User directory configuration
    'path_attribute' => 'name',
],
```

### Publishing Assets

Publish the necessary assets:

```bash
# Publish configuration
php artisan vendor:publish --tag=kodexplorer-config

# Publish assets (CSS, JS, images)
php artisan vendor:publish --tag=kodexplorer-assets

# Publish language files (optional)
php artisan vendor:publish --tag=kodexplorer-lang

# Publish plugins (optional)
php artisan vendor:publish --tag=kodexplorer-plugins
```

### Directory Structure

After installation, the package will create the following structure:

```
vendor/kodexplorer/
├── config/
│   └── kodexplorer.php      # Main configuration
├── resources/
│   ├── assets/              # CSS, JS, images
│   ├── lang/                # Language files
│   └── plugins/             # KodExplorer plugins
└── storage/
    └── app/
        └── kodexplorer/
            └── users/
                └── {username}/
                    ├── data/
                    └── recycle_kod/
```

## Usage

### 1. Create Admin User

Using tinker:
```bash
php artisan tinker
>>> \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true
]);
```

### 2. Access KodExplorer

KodExplorer will be available at `/kodexplorer` after logging in through Laravel's authentication.

### 3. User Management

- Admin users (`is_admin = true`) have full access
- Regular users get their own isolated directory
- User settings are stored per-user in their data directory

## Security

### File Access

- Each user has their own isolated directory
- Users cannot access other users' directories
- Admin users have access to all directories

### Authentication

- Uses Laravel's built-in authentication
- Session management handled by Laravel
- CSRF protection enabled
- Role-based access control available

## Troubleshooting

### Storage Permissions

If you encounter permission issues:
```bash
chmod -R 775 storage/app/kodexplorer
chown -R www-data:www-data storage/app/kodexplorer
```

### Session Issues

Make sure your Laravel session configuration is correct in `config/session.php`.

### User Directory Issues

If user directories aren't created:
```bash
php artisan kodexplorer:repair
# This will scan for missing directories and create them
```

## Advanced Configuration

### Custom User Directory Names

You can customize how user directories are named:

```php
'user_mapping' => [
    'path_attribute' => 'email',  // Use email instead of name
],
```

### Custom Role Mapping

For complex role structures:

```php
'user_mapping' => [
    'role_map' => [
        'admin' => '1',
        'editor' => '2',
        'viewer' => '3',
    ],
],
```

## Events

KodExplorer fires Laravel events that you can listen to:

- `KodExplorerUserLogin`: When a user accesses KodExplorer
- `KodExplorerFileUpload`: When a file is uploaded
- `KodExplorerFileDelete`: When a file is deleted

## API Integration

If you need programmatic access:

```php
use Senasgr\KodExplorer\Facades\KodExplorer;

// Get user's root directory
$rootDir = KodExplorer::getUserDirectory(Auth::user());

// List files
$files = KodExplorer::listFiles($rootDir);

// Upload file
KodExplorer::uploadFile($file, $rootDir);
```
