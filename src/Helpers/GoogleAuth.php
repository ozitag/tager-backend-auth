<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;

class GoogleAuth
{
    private function getClientId(string $provider): ?string
    {
        $value = config('tager-auth.' . $provider . '.googleAuthClientId');

        if (!empty($value)) {
            if (strpos($value, '.apps.googleusercontent.com') === false) {
                return $value . '.apps.googleusercontent.com';
            }
        }

        return $value;
    }

    public function isEnabled(string $provider): bool
    {
        return $this->getClientId($provider) !== null;
    }

    public function getEmailByIdToken(string $provider, string $idToken): ?string
    {
        $client = new \Google_Client([
            'client_id' => $this->getClientId($provider)
        ]);

        $payload = $client->verifyIdToken($idToken);

        return $payload['email'] ?? null;
    }
}
