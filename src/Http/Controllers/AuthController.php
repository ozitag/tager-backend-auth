<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Controllers;

use Illuminate\Http\Request;
use OZiTAG\Tager\Backend\Auth\Http\Features\AuthFeature;
use OZiTAG\Tager\Backend\Auth\Http\Features\RefreshFeature;
use OZiTAG\Tager\Backend\Core\Controllers\Controller;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('grantType') === 'refresh_token') {
            $feature = RefreshFeature::class;
        } else {
            $feature = AuthFeature::class;
        }

        return $this->serve($feature);
    }
}
