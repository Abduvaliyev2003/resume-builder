<?php

namespace App\Domains\Profile\DTOs;

use Illuminate\Http\Request;

class UpdatePasswordDTO
{
    public function __construct(
        public readonly string $currentPassword,
        public readonly string $newPassword,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            currentPassword: $request->input('current_password'),
            newPassword:     $request->input('new_password'),
        );
    }
}
