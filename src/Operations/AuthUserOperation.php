<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetAuthUserOrFailJob;
use OZiTAG\Tager\Backend\Auth\Jobs\GetClientOrFailJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Rbac\Facades\Role;

class AuthUserOperation extends Operation
{
    protected int $clientId;
    protected ?string $clientSecret;
    protected string $password;
    protected string $email;

    /**
     * AuthUserOperation constructor.
     * @param int $clientId
     * @param string|null $clientSecret
     * @param string $email
     * @param string $password
     */
    public function __construct(int $clientId, ?string $clientSecret = null, string $email, string $password)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->email = $email;
        $this->password = $password;
    }

    public function handle() : array
    {
        $clientId = $this->run(GetClientOrFailJob::class, [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        $user = $this->run(GetAuthUserOrFailJob::class, [
            'email' => $this->email,
            'password' => $this->password,
            'clientId' => $clientId,
        ]);

        return $this->run(GenerateTokensOperation::class, [
            'clientId' => $clientId,
            'userId' => $user->id,
            'scopes' => Role::getUserScopesByRole($user)
        ]);
    }
}
