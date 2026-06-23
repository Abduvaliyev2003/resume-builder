<?php

namespace App\Domains\AI\Actions;

use App\Domains\AI\Services\AIService;
use App\Domains\AI\Repositories\AIRepositoryInterface;
use App\Domains\AI\Models\AIReview;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;

class RunATSAnalysisAction
{
    public function __construct(
        protected AIService $aiService,
        protected AIRepositoryInterface $aiRepository,
        protected ResumeRepositoryInterface $resumeRepository
    ) {}

    public function execute(string $resumeId): AIReview
    {
        $resume = $this->resumeRepository->findById($resumeId);
        
        $resumeData = [
            'title' => $resume->title,
            'sections' => $resume->sections->map(fn($s) => [
                'section_type' => $s->section_type,
                'content' => $s->content,
            ])->toArray()
        ];

        $result = $this->aiService->analyzeATS($resumeData);

        // Update resume score based on ATS score
        $this->resumeRepository->update($resumeId, ['score' => $result['score']]);

        return $this->aiRepository->createReview([
            'resume_id' => $resumeId,
            'review_type' => 'ats',
            'score' => $result['score'],
            'feedback_data' => $result,
        ]);
    }
}
