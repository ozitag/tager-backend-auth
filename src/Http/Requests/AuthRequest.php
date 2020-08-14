<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clientId' => 'integer|required',
            'email' => 'email|required',
            'password' => 'string|required|min:4',
        ];
    }
}
