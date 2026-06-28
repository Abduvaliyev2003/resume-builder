<?php

namespace App\Domains\User\DTOs;

readonly class TelegramLoginDTO
{
    public function __construct(

        public string $email,

        public string $password,

        public int $telegramId,

        public ?string $telegramUsername,

        public ?string $telegramFirstName,

        public ?string $telegramLastName,

    ) {}

    public static function fromArray(array $data): self
    {
        return new self(

            email: $data['email'],

            password: $data['password'],

            telegramId: $data['telegram_id'],

            telegramUsername: $data['telegram_username'] ?? null,

            telegramFirstName: $data['telegram_first_name'] ?? null,

            telegramLastName: $data['telegram_last_name'] ?? null,

        );
    }
}
