<?php

namespace Senasgr\KodExplorer;

use Illuminate\Support\ServiceProvider;
use Senasgr\KodExplorer\Auth\KodAuthManager;
use Illuminate\Routing\Router;

class KodExplorerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/kodexplorer.php', 'kodexplorer'
        );

        $this->app->singleton('kodexplorer', function ($app) {
            return new KodExplorer();
        });

        $this->app->singleton('kodexplorer.auth', function ($app) {
            return new KodAuthManager();
        });
    }

    public function boot()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('kodexplorer.auth', \Senasgr\KodExplorer\Middleware\KodAuthenticate::class);

        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/kodexplorer.php' => config_path('kodexplorer.php'),
        ], 'config');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/kodexplorer'),
        ], 'kodexplorer-assets');

        // Publish language files
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/kodexplorer'),
        ], 'kodexplorer-lang');

        // Publish plugins
        $this->publishes([
            __DIR__.'/../resources/plugins' => public_path('vendor/kodexplorer/plugins'),
        ], 'kodexplorer-plugins');

        // Load translations
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'kodexplorer');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/views', 'kodexplorer');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\InstallCommand::class,
            ]);
        }
    }
}
