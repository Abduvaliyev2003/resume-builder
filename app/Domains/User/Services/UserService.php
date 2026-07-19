<?php

namespace App\Domains\User\Services;

use App\Domains\User\DTOs\UserRegisterDTO;
use App\Domains\User\DTOs\UserLoginDTO;
use App\Domains\User\Actions\RegisterUserAction;
use App\Domains\User\Actions\LoginUserAction;
use App\Domains\User\Actions\SocialLoginAction;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class UserService
{
    public function __construct(
        protected RegisterUserAction $registerAction,
        protected LoginUserAction $loginAction,
        protected SocialLoginAction $socialLoginAction
    ) {}

    public function register(UserRegisterDTO $dto): array
    {
        return $this->registerAction->execute($dto);
    }

    public function login(UserLoginDTO $dto): array
    {
        return $this->loginAction->execute($dto);
    }

    public function socialLogin(string $provider, SocialiteUser $socialUser): array
    {
        return $this->socialLoginAction->execute($provider, $socialUser);
    }
}
