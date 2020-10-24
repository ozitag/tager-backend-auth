<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetAccessTokenJob;
use OZiTAG\Tager\Backend\Auth\Jobs\GetRefreshTokenJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;

class GenerateTokensOperation extends Operation
{
    protected int $clientId;
    protected int $userId;
    protected array $scopes;
    protected array $roles;

    /**
     * GenerateTokensOperation constructor.
     * @param int $clientId
     * @param int $userId
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(int $clientId, int $userId, array $scopes = [], array $roles = [])
    {
        $this->clientId = $clientId;
        $this->userId = $userId;
        $this->scopes = $scopes;
        $this->roles = $roles;
    }

    public function handle() : array
    {
        $accessToken = $this->run(GetAccessTokenJob::class, [
            'userId' => $this->userId,
            'clientId' => $this->clientId,
            'scopes' => $this->scopes,
            'roles' => $this->roles,
        ]);

        $refreshToken = $this->run(GetRefreshTokenJob::class, [
            'accessToken' => $accessToken
        ]);

        return [$accessToken, $refreshToken];
    }
}
