<?php

namespace Tollbridge\Socialite\Support;

use Exception;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Tollbridge\Socialite\Events\AuthenticationFailure;
use Tollbridge\Socialite\Events\AuthenticationSuccess;
use Tollbridge\Socialite\Events\TriggerCallback;
use Tollbridge\Socialite\Events\TriggerLogin;
use Tollbridge\Socialite\Events\TriggerLogout;

class TollbridgeAuth
{
    public static function routes()
    {
        Route::get(config('tollbridge.routing.login'), function () {
            TriggerLogin::dispatch();

            return Socialite::with('tollbridge')->redirect();
        });

        Route::get(config('tollbridge.routing.logout'), function () {
            TriggerLogout::dispatch();

            return redirect()->intended();
        });

        Route::get(config('tollbridge.routing.callback'), function () {
            TriggerCallback::dispatch();

            try {
                $user = Socialite::with('tollbridge')->user();
                AuthenticationSuccess::dispatch($user);
            } catch (Exception $exception) {
                AuthenticationFailure::dispatch($exception);
            }

            return redirect()->intended();
        });
    }
}
