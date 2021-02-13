<?php

namespace OZiTAG\Tager\Backend\Auth\Operations;

use OZiTAG\Tager\Backend\Auth\Jobs\GetClientOrFailJob;
use OZiTAG\Tager\Backend\Auth\Jobs\RevokeTokensJob;
use OZiTAG\Tager\Backend\Auth\Jobs\ValidateRefreshTokenJob;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthUserRepository;
use OZiTAG\Tager\Backend\Core\Jobs\Operation;
use OZiTAG\Tager\Backend\Rbac\Facades\Role;
use OZiTAG\Tager\Backend\Rbac\Facades\UserAccessControl;

class RefreshTokenOperation extends Operation
{

    public function __construct(
        protected string $refresh_token,
        protected int $client_id,
        protected ?string $client_secret = null
    ) {}

    /**
     * @param AuthUserRepository $userRepository
     * @return array
     */
    public function handle(AuthUserRepository $userRepository) : array
    {
        $client_id = $this->run(GetClientOrFailJob::class, [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        $refresh_token_data = $this->run(ValidateRefreshTokenJob::class, [
            'refresh_token' => $this->refresh_token,
            'client_id' => $client_id,
        ]);

        $this->run(RevokeTokensJob::class, [
            'refresh_token_data' => $refresh_token_data
        ]);

        return $this->run(GenerateTokensOperation::class, [
            'client_id' => $refresh_token_data['client_id'],
            'user_id' => $refresh_token_data['user_id'],
            'scopes' => UserAccessControl::getScopes(
                $userRepository->find($refresh_token_data['user_id'])
            )
        ]);
    }
}
