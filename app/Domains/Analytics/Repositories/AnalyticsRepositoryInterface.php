<?php

namespace App\Domains\Analytics\Repositories;

use App\Domains\Analytics\Models\ActivityLog;
use Illuminate\Support\Collection;

interface AnalyticsRepositoryInterface
{
    public function logActivity(array $data): ActivityLog;
    public function getUserLogs(string $userId): Collection;
    public function getStats(string $userId): array;
}
