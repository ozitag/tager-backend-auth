<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'ip' => $this->ip,
            'email' => $this->email,
            'administratorId' => $this->administrator_id,
            'userAgent' => $this->user_agent,
            'authSuccess' => (boolean) $this->auth_success,
            'grantType' => $this->grant_type,
            'provider' => $this->provider,
        ];
    }
}
