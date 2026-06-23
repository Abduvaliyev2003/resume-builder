<?php

namespace App\Domains\AI\Actions;

use App\Domains\AI\Services\AIService;
use App\Domains\AI\Repositories\AIRepositoryInterface;
use App\Domains\AI\Models\AIReview;

class RunGrammarCheckAction
{
    public function __construct(
        protected AIService $aiService,
        protected AIRepositoryInterface $aiRepository
    ) {}

    public function execute(string $resumeId, string $text): AIReview
    {
        $result = $this->aiService->checkGrammar($text);

        return $this->aiRepository->createReview([
            'resume_id' => $resumeId,
            'review_type' => 'grammar',
            'score' => $result['score'],
            'feedback_data' => $result,
        ]);
    }
}
