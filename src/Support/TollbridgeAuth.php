<?php

namespace Tollbridge\Socialite\Support;

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Tollbridge\Socialite\Events\Logout;
use Tollbridge\Socialite\Events\Attempting;
use Tollbridge\Socialite\Events\Authenticated;

class TollbridgeAuth
{
    public static function routes()
    {
        Route::get(config('tollbridge.routing.logout'), function () {
            Logout::dispatch();

            return redirect()->intended();
        });

        Route::get(config('tollbridge.routing.login'), function () {
            Attempting::dispatch();

            return Socialite::with('tollbridge')->redirect();
        });

        Route::get(config('tollbridge.routing.callback'), function () {
            $user = Socialite::with('tollbridge')->user();
            Authenticated::dispatch($user);

            return redirect()->intended();
        });
    }
}
