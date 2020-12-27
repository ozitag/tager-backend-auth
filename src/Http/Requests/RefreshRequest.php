<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

/**
 * Class RefreshRequest
 * @package OZiTAG\Tager\Backend\Auth\Http\Requests
 *
 * @property string $refreshToken
 */
class RefreshRequest extends FormRequest
{
    public function rules()
    {
        return [
            'refreshToken' => 'string|required',
        ];
    }
}
