<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Requests\RefreshRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\RefreshTokenOperation;
use OZiTAG\Tager\Backend\Core\Features\Feature;

class RefreshFeature extends Feature
{
    public function handle(RefreshRequest $request)
    {
        list($accessToken, $refreshToken) = $this->run(RefreshTokenOperation::class, [
            'refreshToken' => $request->refreshToken,
            'clientSecret' => null,
            'clientId' => 1,
        ]);

        return new OauthResource([
            'token' => (string)$accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'refreshToken' => $refreshToken,
            'scopes' => $accessToken->getScopes(),
        ]);
    }
}
