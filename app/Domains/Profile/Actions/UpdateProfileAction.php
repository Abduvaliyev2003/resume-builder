<?php

namespace App\Domains\Profile\Actions;

use App\Domains\Profile\DTOs\UpdateProfileDTO;
use App\Domains\Profile\Models\UserProfile;
use App\Domains\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UpdateProfileAction
{
    public function execute(User $user, UpdateProfileDTO $dto, ?UploadedFile $avatar = null): UserProfile
    {
        // Get or create user profile
        $profile = $user->profile ?? new UserProfile(['user_id' => $user->id]);

        // Handle avatar upload
        if ($avatar) {
            // Delete old avatar
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
            $path = $avatar->store('avatars', 'public');
            $profile->avatar = $path;
        }

        // Update user name
        $user->name = $dto->name;
        $user->save();

        // Update profile fields
        $profile->username    = $dto->username;
        $profile->phone       = $dto->phone;
        $profile->date_of_birth = $dto->dateOfBirth;
        $profile->gender      = $dto->gender;
        $profile->job_title   = $dto->jobTitle;
        $profile->company     = $dto->company;
        $profile->country     = $dto->country;
        $profile->city        = $dto->city;
        $profile->website     = $dto->website;
        $profile->bio         = $dto->bio;

        if (! $profile->exists) {
            $profile->id = (string) Str::uuid();
            $profile->user_id = $user->id;
        }

        $profile->save();

        return $profile;
    }

    public function removeAvatar(User $user): void
    {
        $profile = $user->profile;

        if (! $profile || ! $profile->avatar) {
            return;
        }

        Storage::disk('public')->delete($profile->avatar);
        $profile->avatar = null;
        $profile->save();
    }
}
