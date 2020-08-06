<?php

namespace Tollbridge\Socialite\Support;

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Tollbridge\Socialite\Events\UserAuthenticatedEvent;

class TollbridgeAuth
{
    public static function routes()
    {
        Route::get(config('tollbridge.routing.login'), function () {
            return Socialite::with('tollbridge')->redirect();
        });

        Route::get(config('tollbridge.routing.callback'), function () {
            $user = Socialite::with('tollbridge')->user();
            UserAuthenticatedEvent::dispatch($user);

            return redirect()->intended();
        });
    }
}
