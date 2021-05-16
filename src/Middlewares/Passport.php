<?php

namespace OZiTAG\Tager\Backend\Auth\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use OZiTAG\Tager\Backend\Auth\Helpers\ProvidersHelper;
use OZiTAG\Tager\Backend\Auth\Jobs\AuthUserByDevRequestJob;

class Passport
{
    /**
     * @param $request
     * @param Closure $next
     * @param string|null $provider
     * @return mixed
     */
    public function handle($request, Closure $next, string $provider = null)
    {
        $grantType = $request->get('grantType');
        $request->merge([
            'grant_type' => $grantType === 'refresh_token' ? $grantType : 'password',
        ]);

        $provider = $provider ?? ProvidersHelper::getProviderFromAlias(
            $request->route('provider')
        );

        Config::set('auth.guards.api.provider', $provider);

        if (Config::get('tager-auth.dev_auth')) {
            dispatch_now(new AuthUserByDevRequestJob(
                $request->header(Config::get('tager-auth.dev_auth_header'))
            ));
        }

        return $next($request);
    }
}
