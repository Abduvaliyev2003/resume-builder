<?php

namespace App\Domains\Resume\Services;

use App\Domains\Resume\DTOs\ResumeDTO;
use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Repositories\ResumeRepositoryInterface;
use App\Domains\Resume\Actions\CreateResumeAction;
use App\Domains\Resume\Actions\UpdateResumeAction;
use App\Domains\Resume\Actions\DuplicateResumeAction;
use Illuminate\Support\Collection;

class ResumeService
{
    public function __construct(
        protected ResumeRepositoryInterface $resumeRepository,
        protected CreateResumeAction $createAction,
        protected UpdateResumeAction $updateAction,
        protected DuplicateResumeAction $duplicateAction
    ) {}

    public function getResumesForUser(string $userId): Collection
    {
        return $this->resumeRepository->getUserResumes($userId);
    }

    public function getResume(string $id): ?Resume
    {
        return $this->resumeRepository->findById($id);
    }

    public function createResume(string $userId, ResumeDTO $dto): Resume
    {
        return $this->createAction->execute($userId, $dto);
    }

    public function updateResume(string $id, ResumeDTO $dto): Resume
    {
        return $this->updateAction->execute($id, $dto);
    }

    public function deleteResume(string $id): bool
    {
        return $this->resumeRepository->delete($id);
    }

    public function duplicateResume(string $id): Resume
    {
        return $this->duplicateAction->execute($id);
    }
}
