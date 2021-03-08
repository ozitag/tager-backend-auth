<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;


use OZiTAG\Tager\Backend\Auth\Operations\AuthClientTokenOperation;
use OZiTAG\Tager\Backend\Auth\Operations\AuthUserOperation;
use OZiTAG\Tager\Backend\Auth\Operations\RefreshTokenOperation;
use OZiTAG\Tager\Backend\Core\Traits\JobDispatcherTrait;

final class TagerAuthHelper
{
    use JobDispatcherTrait;

    public function auth(string $password, string $username, int $client_id, ?string $client_secret = null): array
    {
        return $this->run(AuthUserOperation::class, [
            'password' => $password,
            'username' => $username,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
        ]);
    }

    public function authWithoutPassword(string $username, int $client_id, ?string $client_secret = null): array
    {
        return $this->run(AuthUserOperation::class, [
            'username' => $username,
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'check_password' => false
        ]);
    }

    public function refresh(string $refresh_token, int $client_id, ?string $client_secret = null): array
    {
        return $this->run(RefreshTokenOperation::class, [
            'refresh_token' => $refresh_token,
            'client_secret' => $client_secret,
            'client_id' => $client_id,
        ]);
    }

    public function clientAuth(int $client_id, string $client_secret): mixed
    {
        return $this->run(AuthClientTokenOperation::class, [
            'client_secret' => $client_secret,
            'client_id' => $client_id,
        ]);
    }
}
