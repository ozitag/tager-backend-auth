<?php

return [
    'administrators' => [
        'recaptchaServerKey' => env('RECAPTCHA_SERVER_KEY'),
        'googleAuthClientId' => env('GOOGLE_AUTH_CLIENT_ID')
    ],
    'providers_aliases' => [
        'administrators' => 'admin',
        'users' => 'user',
    ],
    'client_auth_enabled' => false,
    'hash_clients_secrets' => true,
];
