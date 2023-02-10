<?php

namespace OZiTAG\Tager\Backend\Auth\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use OZiTAG\Tager\Backend\Auth\Models\AuthLog;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Core\Repositories\ISearchable;

class AuthLogRepository extends EloquentRepository implements ISearchable
{
    public function __construct(AuthLog $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $uuid
     * @return AdminAuthLog|null
     */
    public function findByUuid(string $uuid): ?AuthLog {
        return $this->model->whereUuid($uuid)->first();
    }

    public function search(?string $provider, ?string $query) {
        $builder = $provider
            ? $this->model->whereProvider($provider)
            : null;
        return $this->searchByQuery($query, $builder);
    }

    /**
     * @param string|null $query
     * @param Builder|null $builder
     * @return Builder|null
     */
    public function searchByQuery(?string $query, Builder $builder = null): ?Builder {
        $builder = $builder ? $builder : $this->model;

        return $builder->where(function ($builder) use ($query) {
            $builder->where('email', 'LIKE', "%$query%")
                ->orWhere('model_id', '=', $query)
                ->orWhere('user_agent', 'LIKE', "%$query%")
                ->orWhere('ip', 'LIKE', "$query%");
        })->orderByDesc('id');
    }
}
