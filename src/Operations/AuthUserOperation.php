<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetAuthUserOrFailJob;
use OZiTAG\Tager\Backend\Auth\Jobs\GetClientOrFailJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Rbac\Facades\Role;
use OZiTAG\Tager\Backend\Rbac\Facades\UserAccessControl;

class AuthUserOperation extends Operation
{
    public function __construct(
        protected int $client_id,
        protected string $username,
        protected ?string $password = null,
        protected ?string $client_secret = null,
        protected bool $check_password = true,
    ) {}

    public function handle() : array
    {
        $client_id = $this->run(GetClientOrFailJob::class, [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        $user = $this->run(GetAuthUserOrFailJob::class, [
            'username' => $this->username,
            'password' => $this->password,
            'check_password' => $this->check_password,
        ]);

        return $this->run(GenerateTokensOperation::class, [
            'client_id' => $client_id,
            'user_id' => $user->id,
            'scopes' => UserAccessControl::getScopes($user),
        ]);
    }
}
