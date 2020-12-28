<?php
namespace OZiTAG\Tager\Backend\Auth\Events;


class TagerAuthRequest
{
    /**
     * TagerAuthRequest constructor.
     * @param string|null $email
     * @param string $grant_type
     * @param string|null $ip
     * @param string|null $user_agent
     * @param string $provider
     * @param string $uuid
     * @param bool|null $success
     */
    public function __construct(
        public ?string $email,
        public string $grant_type,
        public ?string $ip,
        public ?string $user_agent,
        public string $provider,
        public string $uuid,
        public ?bool $success = false,
    ) {}
}
