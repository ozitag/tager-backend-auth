<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

class RefreshRequest extends FormRequest
{
    public function rules()
    {
        return [
            'clientId' => 'integer|required',
            'refreshToken' => 'string|required',
        ];
    }
}
