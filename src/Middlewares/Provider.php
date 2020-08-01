<?php

namespace OZiTAG\Tager\Backend\Auth\Middlewares;

use Closure;
use Illuminate\Support\Facades\Config;

class Provider
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $providers
     * @return mixed
     */
    public function handle($request, Closure $next, $provider)
    {
        Config::set('auth.guards.api.provider', $provider);
        return $next($request);
    }
}
