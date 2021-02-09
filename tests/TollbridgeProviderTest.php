<?php

namespace Tollbridge\Socialite\Tests;

use Laravel\Socialite\Facades\Socialite;
use Tollbridge\Socialite\OauthTwo\User;

class TollbridgeProviderTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->registerRoutes();
        $this->startSession();
    }

    protected function registerRoutes()
    {
        TestRoutes::routes();
    }

    /** @test */
    public function redirect_to_provider_login_page_from_login_route()
    {
        $authUrl = config('tollbridge.account_url').'/oauth/authorize';

        $response = $this->get(config('tollbridge.routing.login'));

        $response->assertStatus(302);

        $this->assertStringContainsString($authUrl, $response->getTargetUrl());
    }

    /** @test */
    public function dispatch_event_on_successful_logout_redirect()
    {
        $response = $this->get(config('tollbridge.routing.logout'));

        $response->assertRedirect();
    }

    /** @test */
    public function dispatch_event_on_successful_authentication_redirect()
    {
        $user = new User;

        Socialite::shouldReceive('driver')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user);

        $response = $this->get(config('tollbridge.routing.callback'));

        $response->assertRedirect();
    }

    /** @test */
    public function middleware_logout_redirect()
    {
        $response = $this->get(config('tollbridge.routing.callback').'?_tollbridge_logout='.time());

        $response->assertStatus(302);

        $this->assertStringContainsString(config('tollbridge.routing.logout'), $response->getTargetUrl());
    }

    /** @test */
    public function middleware_reauth_redirect()
    {
        $response = $this->get(config('tollbridge.routing.callback').'?_tollbridge_reauth='.time());

        $response->assertStatus(302);

        $this->assertStringContainsString(config('tollbridge.routing.login'), $response->getTargetUrl());
    }
}
