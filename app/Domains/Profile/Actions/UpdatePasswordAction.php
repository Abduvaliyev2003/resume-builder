<?php

namespace App\Domains\Profile\Actions;

use App\Domains\Profile\DTOs\UpdatePasswordDTO;
use App\Domains\Profile\Models\UserProfile;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class UpdatePasswordAction
{
    public function execute(User $user, UpdatePasswordDTO $dto): void
    {
        // Verify current password
        if (! Hash::check($dto->currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password you entered is incorrect.'],
            ]);
        }

        $user->password = Hash::make($dto->newPassword);
        $user->save();

        // Record when password was changed
        $profile = $user->profile ?? new UserProfile([
            'id'      => (string) Str::uuid(),
            'user_id' => $user->id,
        ]);

        $profile->password_changed_at = now();

        if (! $profile->exists) {
            $profile->save();
        } else {
            $profile->save();
        }
    }
}
