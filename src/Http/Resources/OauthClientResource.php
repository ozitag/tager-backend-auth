<?php

namespace OZiTAG\Tager\Backend\Auth\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OauthClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'tokenType' => 'Bearer',
            'expiresAt' => Carbon::parse($this->resource['expireDateTime'])->toIso8601ZuluString(),
            'accessToken' => $this->resource['token'],
            'scopes' => $this->resource['scopes'] ?? [],
        ];
    }
}
