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
    /*
     * Схема таблицы oauth_clients.
     *
     * false — старая схема пакета: bigint-ключ, колонки redirect,
     * password_client, personal_access_client. Её заводит миграция
     * add_default_oauth_client, и она же осталась у всех, кто пришёл с
     * Passport <= 12.
     *
     * true — родная схема Passport 13: uuid-ключ, redirect_uris,
     * grant_types. Ставить, только если таблица действительно такая.
     */
    'passport_client_uuids' => env('PASSPORT_CLIENT_UUIDS', false),
    // Не используется с Passport 13: секреты клиентов хэшируются всегда.
    // Оставлено, чтобы не ломать конфиги проектов.
    'hash_clients_secrets' => true,
    'dev_auth' => true,
    'dev_auth_header' => 'Tager-Dev-Authorization',
];
