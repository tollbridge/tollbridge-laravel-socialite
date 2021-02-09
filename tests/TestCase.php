<?php

namespace Tollbridge\Socialite\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Support\Env;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Tollbridge\Socialite\Middleware\TollbridgeRedirects;
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

        $app['config']->set('tollbridge.account_url', Env::get('TOLLBRIDGE_ACCOUNT_URL', 'https://companyname.tollbridge.test'));
        $app['config']->set('tollbridge.client_id', Env::get('TOLLBRIDGE_CLIENT_ID', 'qwerty-123456-qwerty-123456'));
        $app['config']->set('tollbridge.client_secret', Env::get('TOLLBRIDGE_CLIENT_SECRET', 'z1xc2vb4nm5as6df7gh8jk9lq0wertyuiop'));
        $this->loadMiddleware($app);
    }

    protected function loadMiddleware($app)
    {
        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware('Illuminate\Session\Middleware\StartSession');
        $app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware(TollbridgeRedirects::class);
    }
}
