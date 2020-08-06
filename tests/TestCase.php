<?php

namespace Tollbridge\Socialite\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Env;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Tollbridge\Socialite\ServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            SocialiteServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app->useEnvironmentPath(__DIR__.'/../');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        $this->loadSessionMiddleware($app);
        $app['config']->set('tollbridge.account_url', Env::get('TOLLBRIDGE_ACCOUNT_URL'));
        $app['config']->set('tollbridge.client_id', Env::get('TOLLBRIDGE_CLIENT_ID'));
        $app['config']->set('tollbridge.client_secret', Env::get('TOLLBRIDGE_CLIENT_SECRET'));
        $app['config']->set('tollbridge.redirect_url', Env::get('TOLLBRIDGE_REDIRECT_URL'));
    }

    protected function loadSessionMiddleware($app)
    {
        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Illuminate\Session\Middleware\StartSession');
    }
}
