<?php

namespace App\Domains\AI\Actions;

use App\Domains\AI\Services\AIService;
use App\Domains\AI\Repositories\AIRepositoryInterface;
use App\Domains\AI\Models\AIReview;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;

class DetectMissingSectionsAction
{
    public function __construct(
        protected AIService $aiService,
        protected AIRepositoryInterface $aiRepository,
        protected ResumeRepositoryInterface $resumeRepository
    ) {}

    public function execute(string $resumeId): AIReview
    {
        $resume = $this->resumeRepository->findById($resumeId);

        if (!$resume) {
            throw new \InvalidArgumentException('Resume not found.');
        }

        $sectionTypes = $resume->sections->pluck('section_type')->toArray();

        $result = $this->aiService->detectMissingSections($sectionTypes);

        $missingCount = count($result['missing_sections'] ?? []);
        $score = max(100 - ($missingCount * 20), 0);

        return $this->aiRepository->createReview([
            'resume_id' => $resumeId,
            'review_type' => 'missing_sections',
            'score' => $score,
            'feedback_data' => $result,
        ]);
    }
}
