<?php

namespace App\Domains\Resume\Actions;

use App\Domains\Resume\DTOs\ResumeDTO;
use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\Template\Repositories\TemplateRepositoryInterface;
use App\Domains\Resume\Events\ResumeCreatedEvent;
use Illuminate\Support\Facades\DB;

class CreateResumeAction
{
    public function __construct(
        protected ResumeRepositoryInterface $resumeRepository,
        protected TemplateRepositoryInterface $templateRepository,
        protected CalculateResumeScoreAction $calculateScoreAction
    ) {}

    public function execute(string $userId, ResumeDTO $dto): Resume
    {
        return DB::transaction(function () use ($userId, $dto) {
            $user = auth()->user();

            $resume = $this->resumeRepository->create([
                'user_id' => $userId,
                'template_id' => $dto->template_id ?? $this->templateRepository->allActive()->first()?->id,
                'title' => $dto->title,
                'score' => 0,
            ]);

            foreach ($dto->sections as $secDto) {
                $content = $secDto->content;

                if (
                    $secDto->section_type === 'contact' &&
                    $user instanceof \App\Domains\User\Models\User
                ) {
                    if (empty($content['email'])) {
                        $content['email'] = $user->email;
                    }
                    if (empty($content['name'])) {
                        $content['name'] = $user->name;
                    }
                    if ($user->profile) {
                        if (empty($content['phone'])) {
                            $content['phone'] = $user->profile->phone;
                        }
                        if (empty($content['photo']) && $user->profile->avatar) {
                            $content['photo'] = $user->profile->avatar_url;
                        }
                        if (empty($content['address'])) {
                            $addressParts = array_filter([$user->profile->city, $user->profile->country]);
                            if (!empty($addressParts)) {
                                $content['address'] = implode(', ', $addressParts);
                            }
                        }
                        if (empty($content['date_of_birth'])) {
                            $content['date_of_birth'] = $user->profile->date_of_birth?->format('Y-m-d');
                        }
                    }
                }

                $this->resumeRepository->updateOrCreateSection(
                    $resume->id,
                    $secDto->section_type,
                    $content,
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
