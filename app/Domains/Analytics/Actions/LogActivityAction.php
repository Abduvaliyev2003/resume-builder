<?php

namespace App\Domains\Analytics\Actions;

use App\Domains\Analytics\Repositories\AnalyticsRepositoryInterface;
use App\Domains\Analytics\Models\ActivityLog;

class LogActivityAction
{
    public function __construct(
        protected AnalyticsRepositoryInterface $analyticsRepository
    ) {}

    public function execute(?string $userId, string $action, array $details = []): ActivityLog
    {
        return $this->analyticsRepository->logActivity([
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
        ]);
    }
}
