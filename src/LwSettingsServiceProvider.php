<?php

namespace LWSoftBD\LwSettings;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class LwSettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // load route
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // load view
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lw-settings');

        // load migration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish config only
        $this->publishes([
            __DIR__ . '/../config/lw-settings.php' => config_path('lw-settings.php'),
        ], ['lw-settings-config', 'lw-settings-all']);

        // Publish view only
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/lw-settings/settings'),
        ], ['lw-settings-views', 'lw-settings-all']);

        // Publish seeder (optional)
        $this->publishes([
            __DIR__ . '/../database/seeders/LwSettingsSeeder.php' => database_path('seeders/LwSettingsSeeder.php'),
        ], ['lw-settings-seeder', 'lw-settings-all']);

        View::share('documentation', 'http://lwsoftbd.com');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/lw-settings.php',
            'lw-settings'
        );
    }
}