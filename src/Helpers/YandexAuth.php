<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;

class YandexAuth
{
    public function getEmailByAccessToken(string $accessToken): ?string
    {
        $ch = curl_init('https://login.yandex.ru/info');

        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array('format' => 'json'));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $accessToken));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);

        $data = curl_exec($ch);
        $responseInfo = curl_getinfo($ch);
        curl_close($ch);

        if($responseInfo['http_code'] != 200){
            return null;
        }

        $data = json_decode($data, true);
        return $data['default_email'] ?? null;
    }
}
