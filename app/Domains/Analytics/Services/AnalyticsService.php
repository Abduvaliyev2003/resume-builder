<?php

namespace App\Domains\Analytics\Services;

use App\Domains\Analytics\Repositories\AnalyticsRepositoryInterface;
use App\Domains\Analytics\Actions\LogActivityAction;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function __construct(
        protected AnalyticsRepositoryInterface $analyticsRepository,
        protected LogActivityAction $logActivityAction
    ) {}

    public function track(string $userId, string $action, array $details = []): void
    {
        $this->logActivityAction->execute($userId, $action, $details);
    }

    public function getUserStats(string $userId): array
    {
        return $this->analyticsRepository->getStats($userId);
    }

    public function getUserLogs(string $userId): Collection
    {
        return $this->analyticsRepository->getUserLogs($userId);
    }
}
