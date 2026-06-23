<?php

namespace App\Domains\Resume\Policies;

use App\Domains\User\Models\User;
use App\Domains\Resume\Models\Resume;

class ResumePolicy
{
    public function view(User $user, Resume $resume): bool
    {
        return $user->id === $resume->user_id;
    }

    public function update(User $user, Resume $resume): bool
    {
        return $user->id === $resume->user_id;
    }

    public function delete(User $user, Resume $resume): bool
    {
        return $user->id === $resume->user_id;
    }
}
