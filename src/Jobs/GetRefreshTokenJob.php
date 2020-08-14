<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use League\OAuth2\Server\CryptTrait;
use OZiTAG\Tager\Backend\Auth\Grant;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class GetRefreshTokenJob extends Job
{
    use CryptTrait;

    protected $accessToken;

    /**
     * GetRefreshTokenJob constructor.
     * @param $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->setEncryptionKey(app('encrypter')->getKey());
    }

    public function handle(Grant $grant)
    {
        $refreshToken = $grant->getNewRefreshToken($this->accessToken);
        $refreshTokenPayload = json_encode([
            'client_id'        => (int)$this->accessToken->getClient()->getIdentifier(),
            'refresh_token_id' => $refreshToken->getIdentifier(),
            'access_token_id'  => $this->accessToken->getIdentifier(),
            'scopes'           => $this->accessToken->getScopes(),
            'user_id'          => $this->accessToken->getUserIdentifier(),
            'expire_time'      => $refreshToken->getExpiryDateTime()->getTimestamp(),
        ]);

        return $this->encrypt($refreshTokenPayload);
    }
}
