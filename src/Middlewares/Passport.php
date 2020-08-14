<?php

namespace OZiTAG\Tager\Backend\Auth\Middlewares;

use Closure;
use Illuminate\Support\Facades\Config;
use OZiTAG\Tager\Backend\Auth\Helpers\ProvidersHelper;

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
    public function handle($request, Closure $next)
    {
        $grantType = $request->get('grantType');
        $request->merge([
            'grant_type' => $grantType === 'refresh_token' ? $grantType : 'password'
        ]);

        $provider = ProvidersHelper::getProviderFromAlias(
            $request->route('provider')
        );
        Config::set('auth.guards.api.provider', $provider);

        return $next($request);
    }
}
