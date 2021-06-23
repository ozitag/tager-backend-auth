<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Controllers;

use OZiTAG\Tager\Backend\Auth\Http\Features\AuthLogsFeature;
use OZiTAG\Tager\Backend\Core\Controllers\Controller;

class AuthLogController extends Controller
{
    public function index(?string $provider_string = null)
    {
        return $this->serve(AuthLogsFeature::class, [
            'provider' => $provider_string
        ]);
    }
}
