<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
    public function findById(string $id): ?User;
    public function findByProvider(string $provider, string $providerId): ?User;
}
