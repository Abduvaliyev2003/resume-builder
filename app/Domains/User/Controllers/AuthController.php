<?php

namespace App\Domains\User\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\User\Requests\RegisterRequest;
use App\Domains\User\Requests\LoginRequest;
use App\Domains\User\DTOs\UserRegisterDTO;
use App\Domains\User\DTOs\UserLoginDTO;
use App\Domains\User\Services\UserService;
use App\Domains\User\Resources\UserResource;
use App\Domains\User\Requests\TelegramLoginRequest;
use App\Domains\User\DTOs\TelegramLoginDTO;
use App\Domains\User\Services\TelegramAuthService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected TelegramAuthService $telegramAuthService,
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $dto = UserRegisterDTO::fromArray($request->validated());
        $result = $this->userService->register($dto);
        event(new Registered($result['user']));
        auth()->login($result['user']);

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = UserLoginDTO::fromArray($request->validated());
        $result = $this->userService->login($dto);
        auth()->login($result['user']);

        return response()->json([
            'message' => 'Login successful.',
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()),
        ]);
    }

    public function telegramLogin(
        TelegramLoginRequest $request,
    ): JsonResponse {

        $dto = TelegramLoginDTO::fromArray(
            $request->validated()
        );

        $result = $this->telegramAuthService
            ->login($dto);

        if (!empty($result['created'])) {
            event(new Registered($result['user']));
        }

        return response()->json([

            'message' => 'Telegram login successful.',

            'user' => new UserResource(
                $result['user']
            ),

            'token' => $result['token'],

        ]);
    }

    public function getVerificationStatus(Request $request): JsonResponse
    {
        return response()->json([
            'verified' => (bool) $request->user()?->hasVerifiedEmail(),
        ]);
    }

    public function sendVerificationNotification(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ]);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification code sent.',
        ]);
    }

    public function verifyCode(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified.',
            ]);
        }

        $verified = app(\App\Domains\User\Services\EmailVerificationService::class)
            ->verifyCode($user, $request->input('code'));

        if (!$verified) {
            return response()->json([
                'message' => 'The provided verification code is invalid or has expired.',
                'errors' => [
                    'code' => ['The provided verification code is invalid or has expired.']
                ]
            ], 422);
        }

        // Trigger Laravel Verified event if desired (optional but good practice)
        event(new \Illuminate\Auth\Events\Verified($user));

        return response()->json([
            'message' => 'Email verified successfully.',
        ]);
    }

    public function telegramLogout(
        Request $request,
    ): JsonResponse {

        $request->validate([

            'telegram_id' => [
                'required',
                'integer',
            ],

        ]);

        $this->telegramAuthService
            ->logout(
                $request->integer('telegram_id')
            );

        return response()->json([

            'message' => 'Telegram logout successful.',

        ]);
    }

    public function telegramMe(
    Request $request,
): JsonResponse {

    $request->validate([

        'telegram_id' => [
            'required',
            'integer',
        ],

    ]);

    $user = $this->telegramAuthService
        ->me(
            $request->integer('telegram_id')
        );

    if (! $user) {

        return response()->json([

            'message' => 'Session not found.'

        ],404);
    }

    return response()->json([

        'user' => new UserResource($user)

    ]);
}

    // -------------------------------------------------------------------------
    // Social OAuth
    // -------------------------------------------------------------------------

    /** Providers allowed for Social OAuth */
    private const ALLOWED_PROVIDERS = ['google', 'facebook', 'github'];

    /**
     * Redirect the user to the OAuth provider's consent page.
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            abort(404, "Unknown OAuth provider [{$provider}].");
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the callback from the OAuth provider.
     *
     * On success this stores the Sanctum token in localStorage via an inline
     * HTML page (same pattern as the existing login flow) and redirects the
     * browser to /dashboard.
     */
    public function handleProviderCallback(string $provider): Response|RedirectResponse
    {
        if (! in_array($provider, self::ALLOWED_PROVIDERS, true)) {
            abort(404, "Unknown OAuth provider [{$provider}].");
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Throwable $e) {
            return redirect()->route('login')
                ->with('error', 'OAuth authentication failed. Please try again.');
        }

        $result = $this->userService->socialLogin($provider, $socialUser);
        $user   = $result['user'];
        $token  = $result['token'];

        if (! empty($result['created'])) {
            event(new Registered($user));
        }

        // Log the user in to the web session as well
        auth()->login($user);

        // Pass the Sanctum token to the front-end via an inline script that
        // mirrors what the existing JS login/register handlers do.
        $dashboardUrl = route('dashboard');
        $escapedToken = e($token);

        return response(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head><meta charset="utf-8"><title>Redirecting…</title></head>
            <body>
            <script>
                localStorage.setItem('auth_token', '{$escapedToken}');
                window.dispatchEvent(new Event('auth-change'));
                window.location.href = '{$dashboardUrl}';
            </script>
            <noscript>
                <meta http-equiv="refresh" content="0;url={$dashboardUrl}">
            </noscript>
            </body>
            </html>
            HTML
        );
    }
}
