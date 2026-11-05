<?php

namespace OZiTAG\Tager\Backend\Auth\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use OZiTAG\Tager\Backend\Auth\Contracts\UserMaybeBlockedContract;
use OZiTAG\Tager\Backend\Auth\Helpers\ProvidersHelper;
use OZiTAG\Tager\Backend\Auth\Jobs\AuthUserByDevRequestJob;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Passport
{
    /**
     * @param $request
     * @param Closure $next
     * @param string|null $provider
     * @return mixed
     */
    public function handle($request, Closure $next, ?string $provider = null)
    {
        /*
         * Раньше здесь в каждый запрос подмешивалось
         * $request->merge(['grant_type' => ...]).
         *
         * Middleware висит не только на /tager/auth/*, но и на маршрутах
         * админки (AdminServiceProvider вешает 'passport:administrators'),
         * поэтому лишний grant_type оказывался во входных данных любого
         * запроса админки — и попадал в $request->all() контроллеров,
         * которые про OAuth ничего не знают.
         *
         * Ради этого он там и не нужен: из запроса grant_type читает один
         * AuthFeature, и читает со своим умолчанием — get('grant_type',
         * 'password'). Ветка refresh_token уходит в RefreshFeature, который
         * grant_type не смотрит вовсе. Так что merge был не нужен уже и на
         * самих маршрутах авторизации.
         */

        $provider = $provider ?? ProvidersHelper::getProviderFromAlias(
                $request->route('provider')
            );

        Config::set('auth.guards.api.provider', $provider);

        if (Config::get('tager-auth.dev_auth')) {
            dispatch_sync(new AuthUserByDevRequestJob(
                $request->header(Config::get('tager-auth.dev_auth_header'))
            ));
        }

        $user = Auth::user();

        if ($user && $user instanceof UserMaybeBlockedContract && $user->isBlocked()) {
            throw new AccessDeniedHttpException(__('tager-auth::messages.blocked_account'));
        }

        return $next($request);
    }
}
