<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Bridge\ClientRepository;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Validation\Facades\Validation;

class GetClientOrFailJob extends Job
{
    public function __construct(
        protected int $client_id,
        protected ?string $client_secret = null,
        protected string $grant_type = 'password',
    ) {}

    /**
     * @param ClientRepository $repository
     * @return string
     */
    public function handle(ClientRepository $repository)
    {
        $client = $repository->validateClient($this->client_id, $this->client_secret, $this->grant_type);

        if (!$client) {
            Validation::throw('clientId', 'Invalid Client Id or Secret');
        }

        $client = $repository->getClientEntity($this->client_id);

        return $client->getIdentifier();
    }
}
