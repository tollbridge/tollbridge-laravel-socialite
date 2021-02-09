<?php

namespace Tollbridge\Socialite;

use Exception;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Factory;
use Tollbridge\Socialite\Middleware\TollbridgeRedirects;
use Tollbridge\Socialite\OauthTwo\Provider as TollbridgeSocialiteProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        if (! Str::endsWith(config('tollbridge.redirect'), config('tollbridge.routing.callback'))) {
            throw new Exception('Tollbridge Config Exception: tollbridge.redirect and tollbridge.routing.callback should point to the same internal route');
        }

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

        $this->app['router']->pushMiddlewareToGroup('web', TollbridgeRedirects::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tollbridge.php', 'tollbridge');
    }
}
