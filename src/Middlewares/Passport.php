<?php

namespace OZiTAG\Tager\Backend\Auth\Middlewares;

use Closure;
use Illuminate\Support\Facades\Config;

class Passport
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param array $providers
     * @return mixed
     */
    public function handle($request, Closure $next, $provider = null)
    {
        $grantType = $request->get('grant_type');
        $request->merge([
            'grant_type' => $grantType === 'refresh_token' ? $grantType : 'password'
        ]);

        if($provider) {
            Config::set('auth.guards.api.provider', $provider);
        }

        return $next($request);
    }
}
