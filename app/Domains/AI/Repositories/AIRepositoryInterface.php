<?php

namespace App\Domains\AI\Repositories;

use App\Domains\AI\Models\AIReview;
use App\Domains\Analytics\Models\JobTarget;
use Illuminate\Support\Collection;

interface AIRepositoryInterface
{
    public function createReview(array $data): AIReview;
    public function getReviewsForResume(string $resumeId): Collection;
    public function createJobTarget(array $data): JobTarget;
    public function findJobTarget(string $id): ?JobTarget;
}
