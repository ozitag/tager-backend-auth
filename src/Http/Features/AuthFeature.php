<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Events\TagerSuccessAuthRequest;
use OZiTAG\Tager\Backend\Auth\Facades\TagerAuth;
use OZiTAG\Tager\Backend\Auth\Helpers\GoogleRecaptcha;
use OZiTAG\Tager\Backend\Auth\Http\Requests\AuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Validation\Facades\Validation;

class AuthFeature extends Feature
{
    public function handle(AuthRequest $request, GoogleRecaptcha $recaptcha)
    {
        $provider = Config::get('auth.guards.api.provider');

        if ($recaptcha->isEnabled($provider) && $recaptcha->verify($provider, $request->recaptchaToken, $request->ip()) == false) {
            Validation::throw('recaptchaToken', 'Robot detected');
        }

        $uuid = Str::orderedUuid();

        event(new TagerAuthRequest(
            $request->get('email'),
            $request->get('grant_type', 'password'),
            $request->ip(),
            $request->userAgent(),
            $provider,
            $uuid
        ));

        list($accessToken, $refreshToken) = TagerAuth::auth(
            $request->get('password'),
            $request->get('email'),
            $request->get('clientId', 1),
            $request->get('clientSecret'),
        );

        event(new TagerSuccessAuthRequest($provider, $uuid));

        return new OauthResource([
            'token' => (string)$accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'refreshToken' => $refreshToken,
            'scopes' => $accessToken->getScopes(),
        ]);
    }
}
