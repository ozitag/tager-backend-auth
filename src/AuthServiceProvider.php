<?php

namespace OZiTAG\Tager\Backend\Auth;

use OZiTAG\Tager\Backend\Auth\Scopes\TokenProviderScope;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Token;
use OZiTAG\Tager\Backend\Auth\Observers\TokenObserver;
use OZiTAG\Tager\Backend\Auth\Middlewares\Provider;
use OZiTAG\Tager\Backend\Auth\Middlewares\Passport;

class AuthServiceProvider extends EventServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Token::observe(TokenObserver::class);
        Token::addGlobalScope(new TokenProviderScope);

        app('router')->aliasMiddleware('passport', Passport::class);
        app('router')->aliasMiddleware('provider', Provider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        \Laravel\Passport\Passport::routes(null, ['prefix' => 'oauth', 'middleware' => ['passport']]);
    }
}
