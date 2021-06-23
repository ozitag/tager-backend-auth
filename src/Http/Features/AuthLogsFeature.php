<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Features;

use Illuminate\Support\Facades\Request;
use OZiTAG\Tager\Backend\Auth\Http\Resources\AuthLogResource;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthLogRepository;
use OZiTAG\Tager\Backend\Core\Features\Feature;
use OZiTAG\Tager\Backend\Core\Resources\ResourceCollection;

class AuthLogsFeature extends Feature
{
    public function __construct(
        protected ?string $provider = null
    ) {}

    public function handle(AuthLogRepository $repository)
    {
        $this->registerQueryRequest();
        $this->registerQueryRequest();

        $logs = $repository->paginate(
            $repository->search($this->provider, Request::get('query'))
        );

        $logs->transform(function ($item) {
            return new AuthLogResource($item);
        });

        return new ResourceCollection($logs);
    }
}
