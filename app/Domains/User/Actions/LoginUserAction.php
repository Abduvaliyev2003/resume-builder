<?php

namespace App\Domains\User\Actions;

use App\Domains\User\DTOs\UserLoginDTO;
use App\Domains\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function execute(UserLoginDTO $dto): array
    {
        $user = $this->userRepository->findByEmail($dto->email);

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
