<?php

namespace App\Domains\User\Actions;

use App\Domains\User\DTOs\UserRegisterDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepositoryInterface;
use App\Domains\User\Events\UserRegisteredEvent;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserRegisterDTO $dto): array
    {
        $user = $this->userRepository->create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
        ]);

        event(new UserRegisteredEvent($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
