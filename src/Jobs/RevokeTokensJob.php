<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class RevokeTokensJob extends Job
{

    public function __construct(protected array $refresh_token_data) {}

    public function handle(RefreshTokenRepository $refreshTokenRepository, AccessTokenRepository $accessTokenRepository)
    {
        $accessTokenRepository->revokeAccessToken($this->refresh_token_data['access_token_id']);
        $refreshTokenRepository->revokeRefreshToken($this->refresh_token_data['refresh_token_id']);
    }
}
