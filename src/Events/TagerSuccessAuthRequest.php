<?php
namespace OZiTAG\Tager\Backend\Auth\Events;


class TagerSuccessAuthRequest
{
    /**
     * TagerSuccessAuthRequest constructor.
     * @param string $provider
     * @param string $uuid
     */
    public function __construct(
        public string $provider,
        public string $uuid,
    ) {}
}
