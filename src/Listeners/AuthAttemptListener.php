<?php

namespace OZiTAG\Tager\Backend\Auth\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use OZiTAG\Tager\Backend\Auth\Events\TagerAuthRequest;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthLogRepository;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthUserRepository;

class AuthAttemptListener implements ShouldQueue
{
    public function __construct(
        protected AuthLogRepository $repository,
        protected AuthUserRepository $userRepository,
    ) {}

    public function handle(TagerAuthRequest $event)
    {
        if ($event->email) {
            $user = $this->userRepository->findByEmail($event->email);
        }

        $model = $this->repository->findByUuid($event->uuid);
        $model && $this->repository->set($model);
        $this->repository->fillAndSave([
            'uuid' => $event->uuid,
            'model_id' => $user->id ?? null,
            'grant_type' => $event->grant_type,
            'user_agent' => $event->user_agent,
            'email' => $event->email,
            'ip' => $event->ip,
            'auth_success' => $event->success ?? false,
            'provider' => $event->provider,
        ]);
    }
}
