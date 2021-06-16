<?php

namespace OZiTAG\Tager\Backend\Auth\Contracts;

interface UserMaybeBlockedContract
{
    public function isBlocked(): bool;
}
