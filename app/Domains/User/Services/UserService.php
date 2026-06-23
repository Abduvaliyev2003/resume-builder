<?php

namespace App\Domains\User\Services;

use App\Domains\User\DTOs\UserRegisterDTO;
use App\Domains\User\DTOs\UserLoginDTO;
use App\Domains\User\Actions\RegisterUserAction;
use App\Domains\User\Actions\LoginUserAction;

class UserService
{
    public function __construct(
        protected RegisterUserAction $registerAction,
        protected LoginUserAction $loginAction
    ) {}

    public function register(UserRegisterDTO $dto): array
    {
        return $this->registerAction->execute($dto);
    }

    public function login(UserLoginDTO $dto): array
    {
        return $this->loginAction->execute($dto);
    }
}
