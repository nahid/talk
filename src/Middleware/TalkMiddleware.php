<?php

namespace Nahid\Talk\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nahid\Talk\Facades\Talk;

class TalkMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $guard
     * @return mixed
     */

    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            Talk::setAuthUserId(Auth::guard($guard)->user()->id);
        }

        return $next($request);
    }
}
