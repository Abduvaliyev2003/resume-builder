<?php

namespace App\Domains\Analytics\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Analytics\Services\AnalyticsService;
use App\Domains\Analytics\Resources\ActivityLogResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function stats(Request $request): JsonResponse
    {
        $stats = $this->analyticsService->getUserStats($request->user()->id);

        return response()->json([
            'stats' => $stats,
        ]);
    }

    public function logs(Request $request): JsonResponse
    {
        $logs = $this->analyticsService->getUserLogs($request->user()->id);

        return response()->json([
            'logs' => ActivityLogResource::collection($logs),
        ]);
    }
}
