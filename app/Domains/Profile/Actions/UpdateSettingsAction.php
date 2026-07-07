<?php

namespace App\Domains\Profile\Actions;

use App\Domains\Profile\DTOs\UpdateSettingsDTO;
use App\Domains\Profile\Models\UserProfile;
use App\Domains\User\Models\User;
use Illuminate\Support\Str;

class UpdateSettingsAction
{
    public function execute(User $user, UpdateSettingsDTO $dto): UserProfile
    {
        $profile = $user->profile ?? new UserProfile([
            'id'      => (string) Str::uuid(),
            'user_id' => $user->id,
        ]);

        $profile->settings = $dto->toSettingsArray();

        $profile->save();

        return $profile;
    }
}
