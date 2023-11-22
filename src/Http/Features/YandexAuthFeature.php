<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Facades\TagerAuth;
use OZiTAG\Tager\Backend\Auth\Helpers\GoogleAuth;
use OZiTAG\Tager\Backend\Auth\Helpers\YandexAuth;
use OZiTAG\Tager\Backend\Auth\Http\Requests\GoogleAuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Requests\YandexAuthRequest;
use OZiTAG\Tager\Backend\Auth\Http\Resources\OauthResource;
use OZiTAG\Tager\Backend\Auth\Operations\AuthUserOperationWithoutPassword;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Validation\Facades\Validation;
use OZiTAG\Tager\Backend\Utils\Helpers\RequestHelper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class YandexAuthFeature extends Feature
{
    /**
     * @param GoogleAuthRequest $request
     * @param GoogleAuth $googleAuth
     * @return OauthResource
     */
    public function handle(YandexAuthRequest $request, YandexAuth $yandexAuth, RequestHelper $requestHelper)
    {
        $provider = Config::get('auth.guards.api.provider');

        $email = $yandexAuth->getEmailByAccessToken($request->accessToken);
        if (!$email) {
            Validation::throw(null, 'Can not extract email from Yandex Account');
        }

        event(new TagerAuthRequest(
            $email,
            'yandex',
            $requestHelper->getIpAddress(),
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
