<?php

namespace App\Domains\User\Services;

use App\Domains\User\Models\User;
use App\Domains\User\Models\EmailVerificationCode;
use App\Domains\User\Notifications\EmailVerificationCodeNotification;
use Illuminate\Support\Facades\DB;

class EmailVerificationService
{
    private const CODE_TTL_MINUTES = 10;

    /**
     * Generate a new verification code for the user and send it.
     */
    public function generateCode(User $user): string
    {
        return DB::transaction(function () use ($user) {
            // Expire/Delete previous codes for this user
            EmailVerificationCode::where('user_id', $user->id)->delete();

            // Generate 6-digit random code
            $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

            // Save new code
            EmailVerificationCode::create([
                'user_id' => $user->id,
                'code' => $code,
                'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
            ]);

            // Send notification
            $user->notify(new EmailVerificationCodeNotification($code));

            return $code;
        });
    }

    /**
     * Verify the code entered by the user.
     */
    public function verifyCode(User $user, string $code): bool
    {
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $code)
            ->first();

        if (!$verificationCode || $verificationCode->isExpired()) {
            return false;
        }

        DB::transaction(function () use ($user, $verificationCode) {
            // Mark email as verified
            $user->markEmailAsVerified();

            // Delete verification codes
            EmailVerificationCode::where('user_id', $user->id)->delete();
        });

        return true;
    }
}
