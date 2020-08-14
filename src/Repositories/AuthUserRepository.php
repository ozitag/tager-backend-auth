<?php

namespace OZiTAG\Tager\Backend\Auth\Repositories;

use Laravel\Passport\Bridge\User;
use Laravel\Passport\Bridge\UserRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class AuthUserRepository extends Job
{

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username)
    {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        if (method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($username);
        } else {
            $user = (new $model)->where('email', $username)->first();
        }

        if (!$user) {
            return;
        }

        return $user;
    }
}
