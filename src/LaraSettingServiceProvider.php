<?php

namespace Utyemma\LaraSetting;

use Illuminate\Support\ServiceProvider;
use Utyemma\LaraSetting\Commands\CreateSetting;
use Utyemma\LaraSetting\Commands\SeedSettings;

class LaraSettingServiceProvider extends ServiceProvider {

    function boot(){
        $this->registerCommands();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations/2024_11_01_153414_create_settings_table.php');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'larasettings-migrations');
    }

    function register() {
        
    }

    function registerCommands(){
        if($this->app->runningInConsole()){
            $this->commands([
                CreateSetting::class,
                SeedSettings::class
            ]);
        }
    }

}
