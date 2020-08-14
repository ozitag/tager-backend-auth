<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use OZiTAG\Tager\Backend\Auth\Http\Requests\RefreshRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\GenerateTokensOperation;
use OZiTAG\Tager\Backend\Auth\Operations\RefreshTokenOperation;
use OZiTAG\Tager\Backend\Core\Features\Feature;

class RefreshFeature extends Feature
{

    public function handle(RefreshRequest $request)
    {
        list($accessToken, $refreshToken) = $this->run(RefreshTokenOperation::class, [
            'refreshToken' => $request->get('refreshToken'),
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
