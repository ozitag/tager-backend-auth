<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetAccessTokenJob;
use OZiTAG\Tager\Backend\Auth\Jobs\GetRefreshTokenJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;

class GenerateTokensOperation extends Operation
{
    public function __construct(
        protected int $client_id,
        protected int $user_id,
        protected array $scopes = []
    ) {}

    public function handle(): array
    {
        $accessToken = $this->run(GetAccessTokenJob::class, [
            'user_id' => $this->user_id,
            'client_id' => $this->client_id,
            'scopes' => $this->scopes,
        ]);

        $refreshToken = $this->run(GetRefreshTokenJob::class, [
            'accessToken' => $accessToken
        ]);

        return [$accessToken, $refreshToken];
    }
}
