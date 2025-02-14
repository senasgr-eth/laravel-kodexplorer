# KodExplorer Laravel Integration - Development Guide

## Local Development Setup

### 1. Create a Laravel Project for Testing
```bash
composer create-project laravel/laravel kodexplorer-test
cd kodexplorer-test
```

### 2. Link Local KodExplorer Package
Add this to your Laravel project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../path/to/your/kodexplorer"
        }
    ],
    "require": {
        "senasgr-eth/laravel-kodexplorer": "*"
    }
}
```

Then run:
```bash
composer update
```

### 3. Configure Environment
Prepare your database:
```bash
# Add is_admin column to users table if not exists
php artisan make:migration add_is_admin_to_users_table
php artisan migrate

# Create a test admin user
php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'is_admin' => true]);
```

Optional environment variables:
```env
# KodExplorer uses Laravel auth by default
# KODEXPLORER_AUTH_MODE=laravel
KODEXPLORER_AUTO_LOGIN=false
KODEXPLORER_PASSWORD_CHECK=true
```

### 4. Install Package
```bash
php artisan kodexplorer:install
```

## Development Workflow

### 1. Making Changes
1. Create a new branch for your feature/fix:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes in the package directory
3. Test changes in your Laravel test project
4. Commit your changes with meaningful messages

### 2. Testing
1. Create test Laravel project if you haven't:
   ```bash
   composer create-project laravel/laravel kodexplorer-test
   ```

2. Configure test project:
   ```bash
   cd kodexplorer-test
   composer require senasgr-eth/laravel-kodexplorer:dev-main
   php artisan kodexplorer:install
   ```

3. Test the authentication:
   - Default (Laravel auth):
     ```bash
     # Login with Laravel user credentials
     php artisan serve
     ```
   - Optional standalone mode:
     ```env
     KODEXPLORER_AUTH_MODE=standalone
     ```

### 3. Common Development Tasks

#### Adding New Configuration
1. Add new options to `config/kodexplorer.php`
2. Update `src/KodExplorerServiceProvider.php` if needed
3. Document new options in README.md

#### Modifying Authentication
1. Update `src/Auth/KodAuthManager.php`
2. Test both authentication modes
3. Update relevant middleware if needed

#### Adding New Features
1. Create new classes in appropriate directories
2. Update service provider if needed
3. Add any new configuration options
4. Document new features

#### Asset Management
1. Place new assets in `resources/`
2. Update `webpack.mix.js` if using Laravel Mix
3. Compile assets:
   ```bash
   npm run dev
   ```
4. Update asset publishing in service provider if needed

## Package Structure
```
kodexplorer/
├── app/                    # Original KodExplorer files
├── config/                # Package configuration
├── src/                   # Package source code
│   ├── Auth/             # Authentication related classes
│   ├── Commands/         # Artisan commands
│   ├── Middleware/       # Laravel middleware
│   └── Facades/          # Laravel facades
├── resources/            # Frontend resources
├── routes/               # Package routes
└── tests/               # Package tests
```

## Publishing to Packagist

1. Update `composer.json`:
   - Ensure version number is correct
   - Check all dependencies are listed
   - Verify autoload settings

2. Create new release on GitHub:
   - Tag version following semver (e.g., v1.0.0)
   - Include detailed release notes

3. Package will automatically be updated on Packagist

## Contributing Guidelines

1. Fork the repository
2. Create feature branch
3. Follow PSR-12 coding standards
4. Write/update tests if applicable
5. Submit pull request with detailed description

## Troubleshooting

### Common Issues

1. **Storage Permissions**
   ```bash
   chmod -R 775 storage/app/kodexplorer
   chown -R www-data:www-data storage/app/kodexplorer
   ```

2. **Session Issues**
   - Verify session configuration in `config/session.php`
   - Check session driver settings

3. **Asset Not Found**
   ```bash
   php artisan vendor:publish --tag=kodexplorer-assets --force
   ```

4. **Authentication Issues**
   - Verify `.env` settings
   - Check Laravel auth configuration
   - Ensure user model has required attributes

### Debug Mode
Enable debug mode in your Laravel application's `.env`:
```
APP_DEBUG=true
```

## Support

For bugs and feature requests, please use the GitHub issue tracker.
