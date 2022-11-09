<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Facades\TagerAuth;
use OZiTAG\Tager\Backend\Auth\Helpers\GoogleAuth;
use OZiTAG\Tager\Backend\Auth\Http\Requests\GoogleAuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\AuthUserOperationWithoutPassword;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Validation\Facades\Validation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GoogleAuthFeature extends Feature
{
    /**
     * @param GoogleAuthRequest $request
     * @param GoogleAuth $googleAuth
     * @return OauthResource
     */
    public function handle(GoogleAuthRequest $request, GoogleAuth $googleAuth)
    {
        $provider = Config::get('auth.guards.api.provider');

        if ($googleAuth->isEnabled($provider) == false) {
            throw new NotFoundHttpException('Not Supported');
        }

        $email = $googleAuth->getEmailByIdToken($provider, $request->idToken);

        if (!$email) {
            Validation::throw(null, 'Can not extract email from Google Account');
        }

        $ipAddresses = $request->ips();

        event(new TagerAuthRequest(
            $email,
            'google',
            $request->ip(),
            $request->userAgent(),
            $provider,
            Str::orderedUuid(),
            true
        ));

        list($accessToken, $refreshToken) = TagerAuth::authWithoutPassword(
            $email, $request->get('clientId', 1),
            $request->get('clientSecret'),
        );

        return new OauthResource([
            'token' => (string)$accessToken,
            'expireDateTime' => $accessToken->getExpiryDateTime(),
            'refreshToken' => $refreshToken,
            'scopes' => $accessToken->getScopes(),
        ]);
    }
}
