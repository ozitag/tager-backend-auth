<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Events\TagerSuccessAuthRequest;
use OZiTAG\Tager\Backend\Auth\Helpers\GoogleRecaptcha;
use OZiTAG\Tager\Backend\Auth\Http\Requests\AuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\AuthUserOperation;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Validation\ValidationException;

class AuthFeature extends Feature
{
    public function handle(AuthRequest $request, GoogleRecaptcha $recaptcha)
    {
        $provider = Config::get('auth.guards.api.provider');

        if ($recaptcha->isEnabled($provider) && $recaptcha->verify($provider, $request->recaptchaToken, $request->ip()) == false) {
            throw ValidationException::field('recaptchaToken', 'Robot detected');
        }

        $uuid = Str::orderedUuid();

        event(new TagerAuthRequest(
            $request->get('email'),
            $request->get('grant_type'),
            $request->ip(),
            $request->userAgent(),
            $provider,
            $uuid
        ));

        list($accessToken, $refreshToken) = $this->run(AuthUserOperation::class, [
            'password' => $request->password,
            'email' => $request->email,
            'clientSecret' => null,
            'clientId' => 1,
        ]);

        event(new TagerSuccessAuthRequest(
            Config::get('auth.guards.api.provider'),
            $uuid,
        ));

        return new OauthResource([
            'token' => (string)$accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'refreshToken' => $refreshToken,
            'scopes' => $accessToken->getScopes(),
        ]);
    }
}
