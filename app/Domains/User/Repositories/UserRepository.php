<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Models\User;

class UserRepository implements UserRepositoryInterface
{   
    public  function allUsers(): array
    {
        return User::all()->toArray();
    }
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(string $id): ?User
    {
        return User::find($id);
    }

    public function findByProvider(string $provider, string $providerId): ?User
    {
        return User::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }
}
