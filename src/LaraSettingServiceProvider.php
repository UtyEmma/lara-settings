<?php

namespace Utyemma\LaraSetting;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Utyemma\LaraSetting\Commands\CreateSetting;
use Utyemma\LaraSetting\Commands\SeedSettings;
use Utyemma\LaraSetting\Support\DiscoverClasses;

class LaraSettingServiceProvider extends ServiceProvider {

    // private array $settingClasses = [];

    function boot(){
        $this->registerCommands();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations/2024_11_01_153414_create_settings_table.php');
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'larasettings-migrations');
    }

    // function discover(){
    //     $namespace = 'App\\Settings'; 
    //     $directory = app_path('Settings');

    //     $this->settingClasses = (new DiscoverClasses)->find($namespace, $directory);
    // }

    // function register() {
    //     $this->discover();
    //     foreach ($this->settingClasses as $key => $className) {
    //         $this->app->bind($className, fn () => new $className());
    //         $this->app->singleton($className, fn ($app) => new $className());
    //     }  
    // }

    function registerCommands(){
        if($this->app->runningInConsole()){
            $this->commands([
                CreateSetting::class,
                SeedSettings::class
            ]);
        }
    }

    // function provides(){
    //     $this->discover();
    //     return $this->settingClasses;
    // }

}
