<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use OZiTAG\Tager\Backend\Auth\Grant;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class GetClientAccessTokenJob extends Job
{
    public function __construct(
        protected int $client_id, protected array $scopes = []
    ) {}

    public function handle(Grant $grant, AuthorizationServer $authorizationServer)
    {
        $authorizationServer->enableGrantType($grant, Passport::personalAccessTokensExpireIn());
        return $grant->getNewAccessToken(null, $this->client_id, $this->scopes);
    }
}
