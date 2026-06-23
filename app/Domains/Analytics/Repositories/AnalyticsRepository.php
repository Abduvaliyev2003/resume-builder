<?php

namespace App\Domains\Analytics\Repositories;

use App\Domains\Analytics\Models\ActivityLog;
use App\Domains\Resume\Models\Resume;
use App\Domains\File\Models\GeneratedFile;
use App\Domains\AI\Models\AIReview;
use Illuminate\Support\Collection;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    public function logActivity(array $data): ActivityLog
    {
        return ActivityLog::create($data);
    }

    public function getUserLogs(string $userId): Collection
    {
        return ActivityLog::where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    public function getStats(string $userId): array
    {
        $resumesCount = Resume::where('user_id', $userId)->count();
        
        $resumeIds = Resume::where('user_id', $userId)->pluck('id')->toArray();
        
        $exportsCount = GeneratedFile::whereIn('resume_id', $resumeIds)->count();
        
        $aiReviewsCount = AIReview::whereIn('resume_id', $resumeIds)->count();

        return [
            'total_resumes' => $resumesCount,
            'total_exports' => $exportsCount,
            'total_ai_reviews' => $aiReviewsCount,
        ];
    }
}
