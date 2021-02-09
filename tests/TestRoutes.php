<?php

namespace Tollbridge\Socialite\Tests;

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

class TestRoutes
{
    public static function routes()
    {
        Route::get(config('tollbridge.routing.login'), function () {
            return Socialite::driver('tollbridge')->redirect();
        });

        Route::get(config('tollbridge.routing.logout'), function () {
            return redirect()->intended();
        });

        Route::get(config('tollbridge.routing.callback'), function () {
            $user = Socialite::driver('tollbridge')->user();

            return redirect()->intended();
        });
    }
}
