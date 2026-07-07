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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
