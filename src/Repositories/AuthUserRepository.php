<?php

namespace OZiTAG\Tager\Backend\Auth\Repositories;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Bridge\User;
use Laravel\Passport\Bridge\UserRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;

class AuthUserRepository extends EloquentRepository
{

    public function __construct(\Illuminate\Foundation\Auth\User $model)
    {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        parent::__construct(new $model);
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username)
    {

        if (method_exists($this->model, 'findForPassport')) {
            $user = $this->model->findForPassport($username);
        } else {
            $user = $this->model->where('email', $username)->first();
        }

        if (!$user) {
            return;
        }

        return $user;
    }
}
