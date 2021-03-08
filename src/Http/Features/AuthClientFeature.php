<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use OZiTAG\Tager\Backend\Auth\Facades\TagerAuth;
use OZiTAG\Tager\Backend\Auth\Http\Requests\AuthClientRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthClientResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthClientFeature extends Feature
{
    public function handle(AuthClientRequest $request)
    {
        if (!Config::get('tager-auth.client_auth_enabled', false)) {
            throw new NotFoundHttpException();
        }

        $accessToken = TagerAuth::clientAuth(
            $request->get('clientId'),
            $request->get('clientSecret'),
        );

        return new OauthClientResource([
            'token' => (string) $accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'scopes' => $accessToken->getScopes(),
        ]);
    }
}
