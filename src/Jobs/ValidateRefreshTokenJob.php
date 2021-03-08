<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Laravel\Passport\Bridge\RefreshTokenRepository;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestEvent;
use OZiTAG\Tager\Backend\Auth\Grant;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class ValidateRefreshTokenJob extends Job
{
    use CryptTrait;

    public function __construct(
        protected string $refresh_token,
        protected int $client_id
    ) {
        $this->setEncryptionKey(app('encrypter')->getKey());
    }

    /**
     * @param RefreshTokenRepository $repository
     * @return array
     * @throws OAuthServerException
     */
    public function handle(RefreshTokenRepository $repository) : array
    {
        $refreshToken = $this->withExceptionHandler(
            fn () => $this->decrypt($this->refresh_token)
        );
        $refreshTokenData = json_decode($refreshToken, true);

        if ($refreshTokenData['client_id'] !== $this->client_id) {
            throw OAuthServerException::invalidRefreshToken('Token is not linked to client');
        }

        if ($refreshTokenData['expire_time'] < time()) {
            throw OAuthServerException::invalidRefreshToken('Token has expired');
        }

        if ($repository->isRefreshTokenRevoked($refreshTokenData['refresh_token_id']) === true) {
            throw OAuthServerException::invalidRefreshToken('Token has been revoked');
        }

        return $refreshTokenData;
    }
}
