<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetClientOrFailJob;
use OZiTAG\Tager\Backend\Auth\Jobs\RevokeTokensJob;
use OZiTAG\Tager\Backend\Auth\Jobs\ValidateRefreshTokenJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;

class RefreshTokenOperation extends Operation
{
    protected int $clientId;
    protected ?string $clientSecret;
    protected string $refreshToken;

    /**
     * RefreshTokenOperation constructor.
     * @param int $clientId
     * @param string|null $clientSecret
     * @param string $refreshToken
     */
    public function __construct(int $clientId, ?string $clientSecret = null, string $refreshToken)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->refreshToken = $refreshToken;
    }

    public function handle() : array
    {
        $clientId = $this->run(GetClientOrFailJob::class, [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ]);

        $refreshTokenData = $this->run(ValidateRefreshTokenJob::class, [
            'refreshToken' => $this->refreshToken,
            'clientId' => $clientId,
        ]);

        $this->run(RevokeTokensJob::class, [
            'refreshTokenData' => $refreshTokenData
        ]);

        return $this->run(GenerateTokensOperation::class, [
            'clientId' => $refreshTokenData['client_id'],
            'userId' => $refreshTokenData['user_id']
        ]);
    }
}
