<?php

namespace LWSoftBD\LwSettings;

use Illuminate\Support\ServiceProvider;

class LwSettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // রাউটস
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');

        // ভিউ পাবলিশ
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/lw-settings/settings'),
        ], 'lw-settings-views');

        // ভিউ লোড করা
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lw-settings');

        // মাইগ্রেশন লোড
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // কনফিগ পাবলিশ
        $this->publishes([
            __DIR__ . '/../config/lw-settings.php' => config_path('lw-settings.php'),
        ], 'lw-settings-config');

        
        // Publish seeder (optional)
        $this->publishes([
            __DIR__ . '/../database/seeders/DefaultSettingsSeeder.php' => app_path('/packages/lw-settings/seeder/DefaultSettingsSeeder.php'),
        ], 'lw-settings-seeder');
        
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/lw-settings.php',
            'lw-settings'
        );
    }
}