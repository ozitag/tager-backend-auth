<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use OZiTAG\Tager\Backend\Auth\Http\Requests\AuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\AuthUserOperation;
use OZiTAG\Tager\Backend\Core\Features\Feature;

class AuthFeature extends Feature
{
    public function handle(AuthRequest $request)
    {
        list($accessToken, $refreshToken) = $this->run(AuthUserOperation::class, [
            'password' => $request->get('password'),
            'email' => $request->get('email'),
            'clientSecret' => $request->get('clientSecret'),
            'clientId' => $request->get('clientId'),
        ]);

        return new OauthResource([
            'token' => (string)$accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'refreshToken' => $refreshToken
        ]);
    }
}
