<?php

namespace Tollbridge\Socialite\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Tollbridge\Socialite\Events\Attempting;
use Tollbridge\Socialite\Events\Authenticated;
use Tollbridge\Socialite\Events\Logout;
use Tollbridge\Socialite\OauthTwo\User;
use Tollbridge\Socialite\Support\TollbridgeAuth;

class TollbridgeProviderTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->registerAuthRoutes();
        $this->startSession();
    }

    protected function registerAuthRoutes()
    {
        TollbridgeAuth::routes();
    }

    /** @test */
    public function redirect_to_provider_login_page_from_login_route()
    {
        Event::fake();

        $authUrl = config('tollbridge.account_url').'/oauth/authorize';

        $response = $this->get(config('tollbridge.routing.login'));

        $response->assertStatus(302);

        $this->assertStringContainsString($authUrl, $response->getTargetUrl());
        Event::assertDispatched(Attempting::class);
    }

    /** @test */
    public function dispatch_event_on_successful_logout_redirect()
    {
        Event::fake();

        $response = $this->get(config('tollbridge.routing.logout'));

        $response->assertRedirect();
        Event::assertDispatched(Logout::class);
    }

    /** @test */
    public function dispatch_event_on_successful_authentication_redirect()
    {
        Event::fake();
        $user = new User;

        Socialite::shouldReceive('with')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user);

        $callbackResponse = $this->get(config('tollbridge.routing.callback'));

        $callbackResponse->assertRedirect();
        Event::assertDispatched(Authenticated::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
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

    /** @test */
    public function middleware_callback_indended_redirect()
    {
        Event::fake();
        $user = new User;

        Socialite::shouldReceive('with')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user);

        $url = 'https://localhost/987654321';
        $response = $this->get(config('tollbridge.routing.callback').'?_tollbridge_redirect='.$url);

        $response->assertStatus(302);

        $this->assertStringContainsString($url, $response->getTargetUrl());
    }

    /** @test */
    public function middleware_logout_indended_redirect()
    {
        Event::fake();

        $url = 'https://localhost/123456789';
        $response = $this->get(config('tollbridge.routing.logout').'?_tollbridge_redirect='.$url);

        $response->assertStatus(302);

        $this->assertStringContainsString($url, $response->getTargetUrl());
    }
}
