<?php

namespace OZiTAG\Tager\Backend\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use OZiTAG\Tager\Backend\Auth\Helpers\TagerAuthClientHelper;

/**
 * Class TagerClientAuth
 * @package OZiTAG\Tager\Backend\Core\Auth
 *
 *
 */
class TagerClientAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TagerAuthClientHelper::class;
    }
}
