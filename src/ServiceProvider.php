<?php

namespace Tollbridge\Socialite;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Tollbridge\Socialite\Middleware\TollbridgeRedirects;
use Tollbridge\Socialite\OauthTwo\Provider as TollbridgeSocialiteProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tollbridge.php' => config_path('tollbridge.php'),
            ], 'tollbridge-config');
        }

        $socialite = $this->app->make(Factory::class);
        $socialite->extend(
            'tollbridge',
            function ($app) use ($socialite) {
                return $socialite->buildProvider(TollbridgeSocialiteProvider::class, config('tollbridge'));
            }
        );

        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(TollbridgeRedirects::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tollbridge.php', 'tollbridge');
    }
}
