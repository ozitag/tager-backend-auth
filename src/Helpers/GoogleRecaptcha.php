<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;

class GoogleRecaptcha
{
    private function getServerKey(string $provider): ?string
    {
        return config('tager-auth.' . $provider . '.recaptchaServerKey');
    }

    public function isEnabled(string $provider): bool
    {
        return !empty($this->getServerKey($provider));
    }

    public function verify(string $provider, ?string $recaptchaClientToken, string $ipAddress): bool
    {
        if (empty($recaptchaClientToken)) {
            return false;
        }

        $post_data = http_build_query([
            'secret' => $this->getServerKey($provider),
            'response' => $recaptchaClientToken,
            'remoteip' => $ipAddress
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            ]
        ]);

        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $result = json_decode($response, true);

        if (!isset($result['success']) || $result['success'] == false) {
            return false;
        }

        // reCAPTCHA v2
        if (!isset($result['score'])) {
            return true;
        }

        // reCAPTCHA v3
        return $result['score'] >= 0.5;
    }
}
