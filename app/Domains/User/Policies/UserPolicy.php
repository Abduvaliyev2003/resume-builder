<?php

namespace App\Domains\User\Policies;

use App\Domains\User\Models\User;

class UserPolicy
{
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}
