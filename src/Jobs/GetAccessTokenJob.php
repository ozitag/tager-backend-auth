<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use OZiTAG\Tager\Backend\Auth\Grant;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class GetAccessTokenJob extends Job
{
    protected int $userId;
    protected int $clientId;
    protected array $scopes;

    /**
     * GetAccessTokenJob constructor.
     * @param int $userId
     * @param int $clientId
     * @param array $scopes
     */
    public function __construct(int $userId, int $clientId, array $scopes = [])
    {
        $this->userId = $userId;
        $this->clientId = $clientId;
        $this->scopes = $scopes;
    }

    public function handle(Grant $grant, AuthorizationServer $authorizationServer)
    {
        $authorizationServer->enableGrantType($grant, Passport::personalAccessTokensExpireIn());
        return $grant->getNewAccessToken($this->userId, $this->clientId, $this->scopes);
    }
}
