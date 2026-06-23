<?php

namespace App\Domains\AI\Actions;

use App\Domains\AI\Services\AIService;
use App\Domains\AI\Repositories\AIRepositoryInterface;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\Analytics\Models\JobTarget;

class AnalyzeJobMatchAction
{
    public function __construct(
        protected AIService $aiService,
        protected AIRepositoryInterface $aiRepository,
        protected ResumeRepositoryInterface $resumeRepository
    ) {}

    public function execute(string $resumeId, string $jobTitle, string $jobDescription): JobTarget
    {
        $resume = $this->resumeRepository->findById($resumeId);

        if (!$resume) {
            throw new \InvalidArgumentException('Resume not found.');
        }
        
        $resumeData = [
            'title' => $resume->title,
            'sections' => $resume->sections->map(fn($s) => [
                'section_type' => $s->section_type,
                'content' => $s->content,
            ])->toArray()
        ];

        $result = $this->aiService->analyzeJobMatch($resumeData, $jobDescription);

        return $this->aiRepository->createJobTarget([
            'resume_id' => $resumeId,
            'job_title' => $jobTitle,
            'job_description' => $jobDescription,
            'match_score' => $result['match_score'],
            'analysis_data' => $result,
        ]);
    }
}
