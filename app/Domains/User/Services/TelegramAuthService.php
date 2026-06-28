<?php

namespace App\Domains\User\Services;

use App\Domains\User\DTOs\TelegramLoginDTO;
use App\Domains\User\Models\User;
use App\Domains\User\Repositories\TelegramSessionRepository;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class TelegramAuthService
{
    public function __construct(

        protected TelegramSessionRepository $telegramRepository,

    ) {}

    /**
     * Login Telegram user.
     */
    public function login(
        TelegramLoginDTO $dto,
    ): array {

        $user = User::where(
            'email',
            $dto->email,
        )->first();

        if (! $user) {

            throw new UnauthorizedHttpException(
                '',
                'Invalid credentials.'
            );
        }

        if (! Hash::check(
            $dto->password,
            $user->password,
        )) {

            throw new UnauthorizedHttpException(
                '',
                'Invalid credentials.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Save Telegram Session
        |--------------------------------------------------------------------------
        */

        $this->telegramRepository->createOrUpdate(
            $user,
            [
                'telegram_id' => $dto->telegramId,

                'telegram_username' => $dto->telegramUsername,

                'telegram_first_name' => $dto->telegramFirstName,

                'telegram_last_name' => $dto->telegramLastName,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Sanctum Token
        |--------------------------------------------------------------------------
        */

        $token = $user
            ->createToken('telegram-bot')
            ->plainTextToken;

        return [

            'user' => $user,

            'token' => $token,

        ];
    }

    /**
     * Logout Telegram user.
     */
    public function logout(
        int $telegramId,
    ): void {

        $user = $this->telegramRepository
            ->findUserByTelegramId(
                $telegramId,
            );

        if (! $user) {
            return;
        }

        $user->tokens()->delete();

        $this->telegramRepository
            ->delete($telegramId);
    }

    /**
     * Get authenticated user.
     */
    public function me(
        int $telegramId,
    ): ?User {

        return $this->telegramRepository
            ->findUserByTelegramId(
                $telegramId,
            );
    }
}
