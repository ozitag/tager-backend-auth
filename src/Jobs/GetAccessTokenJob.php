<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Bridge\Scope;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use OZiTAG\Tager\Backend\Auth\Grant;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class GetAccessTokenJob extends Job
{
    protected int $user_id;
    protected int $client_id;
    protected array $scopes;

    public function __construct(int $user_id, int $client_id, array $scopes = [])
    {
        $this->user_id = $user_id;
        $this->client_id = $client_id;
        $this->scopes = array_map(fn ($item) => new Scope($item), $scopes);
    }

    public function handle(Grant $grant, AuthorizationServer $authorizationServer)
    {
        $authorizationServer->enableGrantType($grant, Passport::personalAccessTokensExpireIn());
        return $grant->getNewAccessToken($this->user_id, $this->client_id, $this->scopes);
    }
}
