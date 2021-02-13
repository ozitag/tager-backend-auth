<?php

namespace OZiTAG\Tager\Backend\Auth;

use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\Client;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class Grant extends AbstractGrant
{
    protected $clientRepository;

    public function __construct(
        RefreshTokenRepository $repository,
        ClientRepository $clientRepository,
        AccessTokenRepository $accessTokenRepository
    )
    {
        $this->refreshTokenRepository = $repository;
        $this->clientRepository = $clientRepository;
        $this->accessTokenRepository = $accessTokenRepository;
        $this->refreshTokenTTL = Passport::refreshTokensExpireIn();
    }

    public function getNewAccessToken($user_id, $client_id, $scopes = []) {
        return $this->issueAccessToken(
            Passport::personalAccessTokensExpireIn(),
            $this->clientRepository->getClientEntity($client_id),
            $user_id,
            $scopes
        );
    }

    public function getNewRefreshToken($accessToken) {
        return $this->issueRefreshToken($accessToken);
    }

    /**
     * Return the grant identifier that can be used in matching up requests.
     *
     * @return string
     */
    public function getIdentifier() {
        return;
    }

    /**
     * Respond to an incoming request.
     *
     * @param ServerRequestInterface $request
     * @param ResponseTypeInterface  $responseType
     * @param DateInterval           $accessTokenTTL
     *
     * @return ResponseTypeInterface
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    ) {
        return;
    }
}
