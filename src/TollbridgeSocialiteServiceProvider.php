<?php

namespace Square1\TollbridgeSocialiteProvider;

use Illuminate\Support\ServiceProvider;
use Square1\TollbridgeSocialiteProvider\Commands\TollbridgeSocialiteProviderCommand;

class TollbridgeSocialiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tollbridge-socialite-provider.php' => config_path('tollbridge-socialite-provider.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => base_path('resources/views/vendor/tollbridge-socialite-provider'),
            ], 'views');

            if (! class_exists('CreatePackageTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_tollbridge_socialite_provider_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_tollbridge_socialite_provider_table.php'),
                ], 'migrations');
            }

            $this->commands([
                TollbridgeSocialiteProviderCommand::class,
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tollbridge-socialite-provider');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tollbridge-socialite-provider.php', 'tollbridge-socialite-provider');
    }
}
