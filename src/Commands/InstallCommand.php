<?php

namespace Senasgr\KodExplorer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'kodexplorer:install';
    protected $description = 'Install KodExplorer package';

    public function handle()
    {
        $this->info('Installing KodExplorer...');

        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'Senasgr\\KodExplorer\\KodExplorerServiceProvider',
            '--tag' => 'config'
        ]);

        // Publish assets
        $this->call('vendor:publish', [
            '--provider' => 'Senasgr\\KodExplorer\\KodExplorerServiceProvider',
            '--tag' => 'assets'
        ]);

        // Publish app files
        $this->call('vendor:publish', [
            '--provider' => 'Senasgr\\KodExplorer\\KodExplorerServiceProvider',
            '--tag' => 'app'
        ]);

        // Create storage directory
        $storagePath = config('kodexplorer.storage_path');
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
            $this->info("Created storage directory: {$storagePath}");
        }

        // Create necessary subdirectories
        $directories = ['data', 'temp', 'recycle_kod'];
        foreach ($directories as $dir) {
            $path = $storagePath . '/' . $dir;
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                $this->info("Created directory: {$path}");
            }
        }

        $this->info('KodExplorer has been installed successfully.');
        
        if (config('kodexplorer.auth_mode') === 'laravel') {
            $this->info("\nTo complete Laravel integration, make sure your User model has the required attributes:");
            $this->info("- " . config('kodexplorer.user_mapping.admin_check') . " (for admin role)");
            $this->info("\nYou may need to create these columns in your users table if they don't exist.");
        }
    }
}
