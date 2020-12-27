<?php

namespace OZiTAG\Tager\Backend\Auth\Jobs;

use Illuminate\Contracts\Hashing\Hasher;
use OZiTAG\Tager\Backend\Auth\Repositories\AuthUserRepository;
use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Core\Validation\ValidationException;

class GetAuthUserOrFailJob extends Job
{
    protected string $email;
    protected ?string $password;
    protected int $clientId;
    protected bool $checkPassword;

    public function __construct(string $email, ?string $password = null, int $clientId, bool $checkPassword = true)
    {
        $this->email = $email;
        $this->password = $password;
        $this->clientId = $clientId;
        $this->checkPassword = $checkPassword;
    }

    public function handle(AuthUserRepository $repository, Hasher $hasher)
    {
        $user = $repository->getUserEntityByUserCredentials(
            $this->email
        );

        if (!$user) {
            throw ValidationException::field('email', 'User Not Found');
        }

        if ($this->checkPassword && !$hasher->check($this->password, $user->getAuthPassword())) {
            throw ValidationException::field('password', 'Invalid Password');
        }

        return $user;
    }
}
