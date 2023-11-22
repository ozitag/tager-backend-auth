<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

/**
 * Class AuthRequest
 * @package OZiTAG\Tager\Backend\Auth\Http\Requests
 *
 * @property string $accessToken
 */
class YandexAuthRequest extends FormRequest
{
    public function rules()
    {
        return [
            'accessToken' => 'string|required',
        ];
    }
}
