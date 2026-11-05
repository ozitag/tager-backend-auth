<?php

namespace OZiTAG\Tager\Backend\Auth\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * Идентификатор клиента, который подставляется, когда запрос его не прислал.
 *
 * Раньше в этом месте стояла единица. Она работала, пока ключ oauth_clients
 * был целым и первого клиента заводила миграция add_default_oauth_client.
 * На чистой установке Passport 13 таблица заводится с uuid-ключом, клиента
 * с id = 1 там нет, и запрос без clientId падал на «Invalid Client Id or
 * Secret».
 *
 * Берём первого неотозванного клиента. На прежних установках это та самая
 * запись с id = 1, так что поведение не меняется.
 */
final class DefaultClient
{
    public static function id(): int|string|null
    {
        $id = DB::table('oauth_clients')
            ->where('revoked', false)
            ->orderBy('created_at')
            ->value('id');

        return $id ?? 1;
    }
}
