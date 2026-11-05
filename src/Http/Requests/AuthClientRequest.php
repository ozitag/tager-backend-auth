<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class AuthClientRequest extends FormRequest
{
    /**
     * clientId принимает либо целое, либо UUID.
     *
     * Ключ oauth_clients зависит от схемы: установки, пришедшие с
     * Passport <= 12, держат целый, а чистая установка Passport 13 заводит
     * таблицу с uuid-ключом. Правило 'integer' отвергало второй вариант, и
     * на свежем проекте вход был невозможен в принципе — какой бы клиент
     * ему ни прислали. Выбор схемы остаётся за tager-auth.passport_client_uuids.
     */
    public function rules()
    {
        return [
            'clientId' => ['required', 'regex:/^(\\d+|[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12})$/'],
            'clientSecret' => 'string|required',
        ];
    }
}
