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

        return response()->json([

            'message' => 'Telegram login successful.',

            'user' => new UserResource(
                $result['user']
            ),

            'token' => $result['token'],

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
