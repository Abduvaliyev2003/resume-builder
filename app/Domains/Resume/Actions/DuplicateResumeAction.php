<?php

namespace App\Domains\Resume\Actions;

use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class DuplicateResumeAction
{
    public function __construct(
        protected ResumeRepositoryInterface $resumeRepository
    ) {}

    public function execute(string $resumeId): Resume
    {
        return DB::transaction(function () use ($resumeId) {
            $original = $this->resumeRepository->findById($resumeId);
            
            $duplicated = $this->resumeRepository->create([
                'user_id' => $original->user_id,
                'template_id' => $original->template_id,
                'title' => $original->title . ' (Copy)',
                'score' => $original->score,
            ]);

            foreach ($original->sections as $section) {
                $this->resumeRepository->updateOrCreateSection(
                    $duplicated->id,
                    $section->section_type,
                    $section->content,
                    $section->order_index
                );
            }

            // Load sections for duplication
            $duplicated->load('sections');

            // Save first version for copy
            $resumeData = [
                'title' => $duplicated->title,
                'template_id' => $duplicated->template_id,
                'sections' => $duplicated->sections->map(fn($s) => [
                    'section_type' => $s->section_type,
                    'content' => $s->content,
                    'order_index' => $s->order_index,
                ])->toArray(),
            ];
            $this->resumeRepository->createVersion($duplicated->id, 1, $resumeData);

            return $duplicated;
        });
    }
}
