<?php

namespace Tollbridge\Socialite\Tests;

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

class TestRoutes
{
    public static function routes()
    {
        Route::get(config('tollbridge.routing.login'), function () {
            return Socialite::with('tollbridge')->redirect();
        });

        Route::get(config('tollbridge.routing.logout'), function () {
            return redirect()->intended();
        });

        Route::get(config('tollbridge.routing.callback'), function () {
            $user = Socialite::with('tollbridge')->user();
            return redirect()->intended();
        });
    }
}