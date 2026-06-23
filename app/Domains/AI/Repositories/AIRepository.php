<?php

namespace App\Domains\AI\Repositories;

use App\Domains\AI\Models\AIReview;
use App\Domains\Analytics\Models\JobTarget;
use Illuminate\Support\Collection;

class AIRepository implements AIRepositoryInterface
{
    public function createReview(array $data): AIReview
    {
        return AIReview::create($data);
    }

    public function getReviewsForResume(string $resumeId): Collection
    {
        return AIReview::where('resume_id', $resumeId)->orderBy('created_at', 'desc')->get();
    }

    public function createJobTarget(array $data): JobTarget
    {
        return JobTarget::create($data);
    }

    public function findJobTarget(string $id): ?JobTarget
    {
        return JobTarget::find($id);
    }
}
