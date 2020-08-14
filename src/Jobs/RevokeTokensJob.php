<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class RevokeTokensJob extends Job
{

    protected array $refreshTokenData;

    /**
     * GetRefreshTokenJob constructor.
     * @param $refreshTokenData
     */
    public function __construct(array $refreshTokenData)
    {
        $this->refreshTokenData = $refreshTokenData;
    }

    public function handle(RefreshTokenRepository $refreshTokenRepository, AccessTokenRepository $accessTokenRepository)
    {
        $accessTokenRepository->revokeAccessToken($this->refreshTokenData['access_token_id']);
        $refreshTokenRepository->revokeRefreshToken($this->refreshTokenData['refresh_token_id']);
    }
}
