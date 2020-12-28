<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Helpers\GoogleAuth;
use OZiTAG\Tager\Backend\Auth\Http\Requests\GoogleAuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\AuthUserOperationWithoutPassword;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Validation\ValidationException;
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
            throw ValidationException::field('email', 'Can not extract email from Google Account');
        }

        event(new TagerAuthRequest(
            $email,
            'google',
            $request->ip(),
            $request->userAgent(),
            $provider,
            Str::orderedUuid(),
            true
        ));

        list($accessToken, $refreshToken) = $this->run(AuthUserOperationWithoutPassword::class, [
            'email' => $email,
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
