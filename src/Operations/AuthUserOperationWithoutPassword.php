<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetAuthUserOrFailJob;
use OZiTAG\Tager\Backend\Auth\Jobs\GetClientOrFailJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Rbac\Facades\Role;
use OZiTAG\Tager\Backend\Rbac\Facades\UserAccessControl;

class AuthUserOperationWithoutPassword extends Operation
{
    protected int $clientId;
    protected ?string $clientSecret;
    protected string $email;

    public function __construct(int $clientId, ?string $clientSecret = null, string $email)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->email = $email;
    }

    public function handle() : array
    {
        $clientId = $this->run(GetClientOrFailJob::class, [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        $user = $this->run(GetAuthUserOrFailJob::class, [
            'checkPassword' => false,
            'email' => $this->email,
            'clientId' => $clientId,
        ]);

        return $this->run(GenerateTokensOperation::class, [
            'clientId' => $clientId,
            'userId' => $user->id,
            'scopes' => UserAccessControl::getScopes($user),
        ]);
    }
}
