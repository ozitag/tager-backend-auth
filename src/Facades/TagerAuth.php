<?php

namespace OZiTAG\Tager\Backend\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use OZiTAG\Tager\Backend\Auth\Helpers\TagerAuthHelper;

/**
 * Class TagerAuth
 * @package OZiTAG\Tager\Backend\Core\Auth
 *
 * @method static array auth(string $password, string $username, int $client_id, ?string $client_secret = null)
 * @method static array authWithoutPassword(string $username, int $client_id, ?string $client_secret = null)
 * @method static array refresh(string $refresh_token, int $client_id, ?string $client_secret = null)
 * @method static mixed clientAuth(int $client_id, string $client_secret)
 */
class TagerAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return TagerAuthHelper::class;
    }
}
