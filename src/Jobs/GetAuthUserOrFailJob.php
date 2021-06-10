<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Illuminate\Contracts\Hashing\Hasher;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthUserRepository;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Validation\Facades\Validation;

class GetAuthUserOrFailJob extends Job
{
    public function __construct(
        protected string $username,
        protected ?string $password = null,
        protected bool $check_password = true
    )
    {
    }

    /**
     * @param AuthUserRepository $repository
     * @param Hasher $hasher
     */
    public function handle(AuthUserRepository $repository, Hasher $hasher)
    {
        $user = $repository->getUserEntityByUserCredentials(
            $this->username
        );

        if (!$user) {
            if ($this->check_password) {
                Validation::throw('password', __('tager-auth::messages.invalid_password'));
            } else {
                Validation::throw(null, __('tager-auth::messages.user_not_found'));
            }
        }

        if ($this->check_password && !$hasher->check($this->password, $user->getAuthPassword())) {
            Validation::throw('password', __('tager-auth::messages.invalid_password'));
        }

        return $user;
    }
}
