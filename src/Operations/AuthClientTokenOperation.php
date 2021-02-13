<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;


use OZiTAG\Tager\Backend\Auth\Jobs\GetClientAccessTokenJob;
use OZiTAG\Tager\Backend\Auth\Jobs\GetClientOrFailJob;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Rbac\Facades\Role;

class AuthClientTokenOperation extends Operation
{

    public function __construct(
        protected int $client_id,
        protected string $client_secret
    ) {}

    public function handle() : mixed
    {
        $client_id = $this->run(GetClientOrFailJob::class, [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'client_credentials'
        ]);

        return $this->run(GetClientAccessTokenJob::class, [
            'client_id' => $client_id,
            'scopes' => [],
        ]);
    }
}
