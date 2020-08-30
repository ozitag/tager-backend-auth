<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use OZiTAG\Tager\Backend\Auth\Grant;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Validation\ValidationException;

class GetClientOrFailJob extends Job
{
    protected int $clientId;
    protected ?string $clientSecret;

    /**
     * @param int $clientId
     */
    public function __construct(int $clientId, ?string $clientSecret = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @param ClientRepository $repository
     * @return string
     * @throws ValidationException
     */
    public function handle(ClientRepository $repository)
    {
        $client = $repository->validateClient($this->clientId, $this->clientSecret, 'password');
        
        if(!$client) {
            throw ValidationException::field('clientId', 'Invalid Client Id or Secret');
        }

        $client = $repository->getClientEntity($this->clientId);

        return $client->getIdentifier();
    }
}
