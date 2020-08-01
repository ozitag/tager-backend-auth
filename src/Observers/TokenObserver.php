<?php

namespace OZiTAG\Tager\Backend\Auth\Observers;

use Illuminate\Support\Facades\Config;
use Laravel\Passport\Token;

class TokenObserver
{
    /**
     * Handle the token "created" event.
     *
     * @param Token $token
     * @return void
     */
    public function creating(Token $token)
    {
        if (!$token->provider) {
            $token->provider = Config::get('auth.guards.api.provider');
        }
    }

    /**
     * Handle the token "updated" event.
     *
     * @param Token $token
     * @return void
     */
    public function updated(Token $token)
    {
        //
    }

    /**
     * Handle the token "deleted" event.
     *
     * @param Token $token
     * @return void
     */
    public function deleted(Token $token)
    {
        //
    }

    /**
     * Handle the token "restored" event.
     *
     * @param Token $token
     * @return void
     */
    public function restored(Token $token)
    {
        //
    }

    /**
     * Handle the token "force deleted" event.
     *
     * @param Token $token
     * @return void
     */
    public function forceDeleted(Token $token)
    {
        //
    }
}
