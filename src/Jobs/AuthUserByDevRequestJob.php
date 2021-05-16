<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use OZiTAG\Tager\Backend\Admin\Models\Administrator;
use OZiTAG\Tager\Backend\Core\Jobs\Job;

class AuthUserByDevRequestJob extends Job
{
    public function __construct(
        protected ?string $header
    ) {}

    public function handle()
    {
        if (!$this->header) {
            return null;
        }

        if (in_array(App::environment(), ['prod', 'production'])) {
            return null;
        }

        $provider = Config::get('auth.providers')[Config::get('auth.guards.api.provider')]['model'] ?? null;

        if (!$provider) {
            return null;
        }

        $user = $provider::whereEmail($this->header)->first();
        $user && Auth::setUser($user);
    }
}
