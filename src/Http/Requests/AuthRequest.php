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
            'email' => 'required|email',
            'password' => 'required|string',
            'recaptchaToken' => 'nullable|string',
            'clientId' => 'nullable|integer',
            'clientSecret' => 'nullable|string',
        ];
    }
}
