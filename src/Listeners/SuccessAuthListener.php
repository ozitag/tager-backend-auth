<?php

namespace OZiTAG\Tager\Backend\Auth\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use OZiTAG\Tager\Backend\Auth\Events\TagerSuccessAuthRequest;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthLogRepository;

class SuccessAuthListener implements ShouldQueue
{

    public function __construct(
        protected AuthLogRepository $repository,
    ) {}
    
    public function handle(TagerSuccessAuthRequest $event)
    {
        $model = $this->repository->findByUuid($event->uuid);
        $model && $this->repository->set($model);
        $this->repository->fillAndSave([
            'uuid' => $event->uuid,
            'auth_success' => true,
        ]);
    }
}
