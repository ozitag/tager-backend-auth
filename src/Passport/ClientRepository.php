<?php

namespace OZiTAG\Tager\Backend\Auth\Passport;

use Laravel\Passport\Bridge\ClientRepository as PassportClientRepository;

/**
 * Публичный клиент снова проходит проверку.
 *
 * Passport 13 требует непустой секрет у любого клиента:
 *
 *     return $record && !empty($clientSecret) && $this->hasher->check(...);
 *
 * Клиент админки — публичный: его заводит миграция add_default_oauth_client
 * без секрета, и экран входа присылает только clientId. По RFC 6749 §2.1
 * такой клиент не аутентифицируется вовсе, а здесь он отвергался с «Invalid
 * Client Id or Secret», то есть вход переставал работать у всех.
 *
 * Схему oauth_clients трогать не нужно: модель Passport 13 сама читает
 * старые колонки redirect и password_client через аксессоры redirectUris()
 * и grantTypes(). Чего она сама не делает — это поиск клиента по целому
 * ключу, за это отвечает Passport::$clientUuids в AuthServiceProvider.
 */
class ClientRepository extends PassportClientRepository
{
    /**
     * {@inheritdoc}
     */
    public function validateClient(string $clientIdentifier, ?string $clientSecret, ?string $grantType): bool
    {
        $record = $this->clients->findActive($clientIdentifier);

        if (!$record) {
            return false;
        }

        if (!$record->confidential()) {
            return true;
        }

        return !empty($clientSecret) && $this->hasher->check($clientSecret, $record->secret);
    }
}
