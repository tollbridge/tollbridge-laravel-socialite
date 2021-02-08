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
        //Logout
        if ($request->has('_tollbridge_logout')) {
            return redirect(config('tollbridge.routing.logout'));
        }

        //Callback
        if ($request->has('_tollbridge_reauth')) {
            return redirect(config('tollbridge.routing.login'));
        }

        return $next($request);
    }
}
