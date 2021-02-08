<?php

namespace Tollbridge\Socialite\Middleware;

use Closure;
use Illuminate\Http\Request;

class TollbridgeRedirects
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //Set redirect uri
        if ($request->has('_tollbridge_redirect')) {
            $request->session()->put('url.intended', $request->input('_tollbridge_redirect'));
        } elseif (session()->has('_tollbridge_reauth_redirect')) {
            $request->session()->put('url.intended', $request->session()->get('_tollbridge_reauth_redirect'));
        }
        $request->session()->forget('_tollbridge_reauth_redirect');

        //Logout
        if ($request->has('_tollbridge_logout')) {
            return redirect(config('tollbridge.routing.logout'));
        }

        //Callback
        if ($request->has('_tollbridge_reauth')) {
            if ($request->has('_tollbridge_redirect')) {
                $request->session()->put('_tollbridge_reauth_redirect', $request->input('_tollbridge_redirect'));
            }

            return redirect(config('tollbridge.routing.login'));
        }

        return $next($request);
    }
}
