<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\TelegramSession;
use App\Domains\User\Models\User;

class TelegramSessionRepository
{
    /**
     * Create or update Telegram session.
     */
    public function createOrUpdate(
        User $user,
        array $telegramData,
    ): TelegramSession {

        return TelegramSession::updateOrCreate(

            [
                'telegram_id' => $telegramData['telegram_id'],
            ],

            [
                'user_id' => $user->id,

                'telegram_username' =>
                    $telegramData['telegram_username'] ?? null,

                'telegram_first_name' =>
                    $telegramData['telegram_first_name'] ?? null,

                'telegram_last_name' =>
                    $telegramData['telegram_last_name'] ?? null,

                'last_login_at' => now(),
            ]

        );
    }

    /**
     * Find by telegram id.
     */
    public function findByTelegramId(
        int $telegramId,
    ): ?TelegramSession {

        return TelegramSession::where(
            'telegram_id',
            $telegramId,
        )->first();
    }

    /**
     * Find user by telegram id.
     */
    public function findUserByTelegramId(
        int $telegramId,
    ): ?User {

        $session = TelegramSession::with('user')
            ->where(
                'telegram_id',
                $telegramId,
            )
            ->first();

        return $session?->user;
    }

    /**
     * Delete telegram session.
     */
    public function delete(
        int $telegramId,
    ): bool {

        return TelegramSession::where(
            'telegram_id',
            $telegramId,
        )->delete();
    }

    /**
     * Check session exists.
     */
    public function exists(
        int $telegramId,
    ): bool {

        return TelegramSession::where(
            'telegram_id',
            $telegramId,
        )->exists();
    }
}