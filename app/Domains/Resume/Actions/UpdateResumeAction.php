<?php

namespace App\Domains\Resume\Actions;

use App\Domains\Resume\DTOs\ResumeDTO;
use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UpdateResumeAction
{
    public function __construct(
        protected ResumeRepositoryInterface $resumeRepository,
        protected CalculateResumeScoreAction $calculateScoreAction
    ) {}

    public function execute(string $resumeId, ResumeDTO $dto): Resume
    {
        return DB::transaction(function () use ($resumeId, $dto) {
            $resume = $this->resumeRepository->update($resumeId, [
                'title' => $dto->title,
                'template_id' => $dto->template_id,
            ]);

            foreach ($dto->sections as $secDto) {
                $this->resumeRepository->updateOrCreateSection(
                    $resume->id,
                    $secDto->section_type,
                    $secDto->content,
                    $secDto->order_index
                );
            }

            // Refresh to load updated sections
            $resume->load('sections');

            // Recalculate score
            $score = $this->calculateScoreAction->execute($resume);
            $resume = $this->resumeRepository->update($resume->id, ['score' => $score]);

            // Get next version number
            $currentMaxVersion = $resume->versions()->max('version_number') ?? 0;
            $nextVersionNumber = $currentMaxVersion + 1;

            // Save new version
            $resumeData = [
                'title' => $resume->title,
                'template_id' => $resume->template_id,
                'sections' => $resume->sections->map(fn($s) => [
                    'section_type' => $s->section_type,
                    'content' => $s->content,
                    'order_index' => $s->order_index,
                ])->toArray(),
            ];
            $this->resumeRepository->createVersion($resume->id, $nextVersionNumber, $resumeData);

            return $resume;
        });
    }
}
