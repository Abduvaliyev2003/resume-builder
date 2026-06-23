<?php

namespace App\Domains\Resume\Actions;

use App\Domains\Resume\DTOs\ResumeDTO;
use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\Resume\Events\ResumeCreatedEvent;
use Illuminate\Support\Facades\DB;

class CreateResumeAction
{
    public function __construct(
        protected ResumeRepositoryInterface $resumeRepository,
        protected CalculateResumeScoreAction $calculateScoreAction
    ) {}

    public function execute(string $userId, ResumeDTO $dto): Resume
    {
        return DB::transaction(function () use ($userId, $dto) {
            $resume = $this->resumeRepository->create([
                'user_id' => $userId,
                'template_id' => $dto->template_id,
                'title' => $dto->title,
                'score' => 0,
            ]);

            foreach ($dto->sections as $secDto) {
                $this->resumeRepository->updateOrCreateSection(
                    $resume->id,
                    $secDto->section_type,
                    $secDto->content,
                    $secDto->order_index
                );
            }

            // Calculate base score
            $score = $this->calculateScoreAction->execute($resume);
            $resume = $this->resumeRepository->update($resume->id, ['score' => $score]);

            // Save first version
            $resumeData = [
                'title' => $resume->title,
                'template_id' => $resume->template_id,
                'sections' => $resume->sections->map(fn($s) => [
                    'section_type' => $s->section_type,
                    'content' => $s->content,
                    'order_index' => $s->order_index,
                ])->toArray(),
            ];
            $this->resumeRepository->createVersion($resume->id, 1, $resumeData);

            event(new ResumeCreatedEvent($resume));

            return $resume;
        });
    }
}
