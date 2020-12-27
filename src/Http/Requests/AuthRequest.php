<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Requests;

use OZiTAG\Tager\Backend\Core\Http\FormRequest;

/**
 * Class AuthRequest
 * @package OZiTAG\Tager\Backend\Auth\Http\Requests
 *
 * @property string $email
 * @property string $password
 * @property string $recaptchaToken
 */
class AuthRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'email|required',
            'password' => 'string|required|min:4',
            'recaptchaToken' => 'nullable|string',
        ];
    }
}
