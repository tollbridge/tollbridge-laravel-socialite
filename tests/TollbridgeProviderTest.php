<?php

namespace Tollbridge\Socialite\Tests;

use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Tollbridge\Socialite\Events\UserAuthenticatedEvent;
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
        $authUrl = config('tollbridge.account_url').'/oauth/authorize';

        $response = $this->get(config('tollbridge.routing.login'));

        $response->assertStatus(302);

        $this->assertStringContainsString($authUrl, $response->getTargetUrl());
    }

    /** @test */
    public function dispatch_event_on_successful_authentication_redirect()
    {
        Event::fake();
        $user = new User;
        $user->id;
        Socialite::shouldReceive('with')
            ->andReturnSelf()
            ->shouldReceive('user')
            ->andReturn($user);

        $callbackResponse = $this->get(config('tollbridge.routing.callback'));

        $callbackResponse->assertRedirect();
        Event::assertDispatched(function (UserAuthenticatedEvent $event) use ($user) {
            return $event->user->id === $user->id;
        });
    }
}
