<?php

namespace App\Domains\User\DTOs;

use App\Shared\DTOs\BaseDTO;

class UserLoginDTO extends BaseDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password
    ) {}
}
