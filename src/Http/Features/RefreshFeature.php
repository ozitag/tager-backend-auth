<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;


use OZiTAG\Tager\Backend\Auth\Facades\TagerAuth;
use OZiTAG\Tager\Backend\Auth\Http\Requests\RefreshRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;

class RefreshFeature extends Feature
{
    public function handle(RefreshRequest $request)
    {
        list($accessToken, $refreshToken) = TagerAuth::refresh(
            $request->get('refreshToken'),
            $request->get('clientId', 1),
            $request->get('clientSecret'),
        );

        return new OauthResource([
            'token' => (string) $accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'refreshToken' => $refreshToken,
            'scopes' => $accessToken->getScopes(),
        ]);
    }
}
