<?php

namespace Senasgr\KodExplorer\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Senasgr\KodExplorer\KodExplorerServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            KodExplorerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // Setup KodExplorer config
        $app['config']->set('kodexplorer.auth_mode', 'laravel');
        $app['config']->set('kodexplorer.storage_path', storage_path('app/kodexplorer'));
    }
}
