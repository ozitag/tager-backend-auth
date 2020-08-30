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

    protected string $refreshToken;
    protected int $clientId;

    /**
     * GetRefreshTokenJob constructor.
     * @param $refreshToken
     * @param $clientId
     */
    public function __construct(string $refreshToken, int $clientId)
    {
        $this->refreshToken = $refreshToken;
        $this->clientId = $clientId;
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
            fn () => $this->decrypt($this->refreshToken)
        );
        $refreshTokenData = json_decode($refreshToken, true);

        if ($refreshTokenData['client_id'] !== $this->clientId) {
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
