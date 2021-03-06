<?php

namespace Tollbridge\Socialite;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Tollbridge\Socialite\Middleware\TollbridgeRedirects;
use Tollbridge\Socialite\OauthTwo\Provider as TollbridgeSocialiteProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tollbridge.php' => config_path('tollbridge.php'),
            ], 'tollbridge-config');
        }

        //Set OAuth redirect URL
        config(['tollbridge.redirect' => url(config('tollbridge.routing.callback'))]);
        config(['tollbridge.account_url' => 'https://'.config('tollbridge.app_id')]);

        $socialite = $this->app->make(Factory::class);
        $socialite->extend(
            'tollbridge',
            function () use ($socialite) {
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
