<?php

namespace Square1\TollbridgeSocialiteProvider;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class TollbridgeSocialiteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tollbridge.php' => config_path('tollbridge.php'),
            ], 'config');
        }

        $socialite = $this->app->make(Factory::class);
        $socialite->extend(
            'tollbridge',
            function ($app) use ($socialite) {
                return $socialite->buildProvider(TollbridgeSocialiteProvider::class, config('tollbridge'));
            }
        );
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tollbridge.php', 'tollbridge');
    }
}
