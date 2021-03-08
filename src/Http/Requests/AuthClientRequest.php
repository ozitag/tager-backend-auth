<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class AuthClientRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clientId' => 'integer|required',
            'clientSecret' => 'string|required',
        ];
    }
}
