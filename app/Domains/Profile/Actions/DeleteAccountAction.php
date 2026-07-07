<?php

namespace App\Domains\Profile\Actions;

use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DeleteAccountAction
{
    public function execute(User $user, string $password): void
    {
        // Verify password before deletion
        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['The password you entered is incorrect.'],
            ]);
        }

        // Delete avatar file if exists
        if ($user->profile && $user->profile->avatar) {
            Storage::disk('public')->delete($user->profile->avatar);
        }

        // Revoke all Sanctum tokens
        $user->tokens()->delete();

        // Delete user (cascade will handle resumes, profile, etc.)
        $user->delete();
    }
}
