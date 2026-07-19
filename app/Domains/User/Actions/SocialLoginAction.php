<?php

namespace App\Domains\User\Actions;

use App\Domains\User\Models\User;
use App\Domains\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialLoginAction
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Find or create a user from a Social OAuth provider response.
     *
     * Priority:
     *   1. Match by provider + provider_id (returning OAuth user)
     *   2. Match by email (link existing email/password account)
     *   3. Create a brand-new user
     *
     * OAuth users are always auto-verified because the provider already
     * verified the email address.
     *
     * @return array{user: User, token: string, created: bool}
     */
    public function execute(string $provider, SocialiteUser $socialUser): array
    {
        $created = false;

        // Extract email, fallback to a dummy unique email if the provider doesn't return one (e.g. GitHub private email)
        $email = $socialUser->getEmail() ?: $socialUser->getId() . '@' . $provider . '.oauth';

        // 1. Try to find by provider + provider_id
        $user = $this->userRepository->findByProvider($provider, $socialUser->getId());

        if (! $user) {
            // 2. Fall back to email match (link existing account)
            $user = $this->userRepository->findByEmail($email);
        }

        if ($user) {
            // Update / fill-in OAuth fields in case they were missing (e.g. email
            // match from a password-based account) or the avatar changed.
            $user->provider          = $provider;
            $user->provider_id       = $socialUser->getId();
            $user->provider_avatar   = $socialUser->getAvatar();

            // Auto-verify email if not already verified
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }

            $user->save();
        } else {
            // 3. Create a new user
            $created = true;

            $user = new User();
            $user->name              = $socialUser->getName() ?: $socialUser->getNickname() ?: 'User';
            $user->email             = $email;
            $user->password          = null; // OAuth users have no password
            $user->provider          = $provider;
            $user->provider_id       = $socialUser->getId();
            $user->provider_avatar   = $socialUser->getAvatar();
            $user->email_verified_at = now(); // Provider already verified
            $user->save();
        }

        $token = $user->createToken('oauth-' . $provider)->plainTextToken;

        return [
            'user'    => $user,
            'token'   => $token,
            'created' => $created,
        ];
    }
}
