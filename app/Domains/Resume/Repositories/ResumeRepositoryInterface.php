<?php

namespace App\Domains\Resume\Repositories;

use App\Domains\Resume\Models\Resume;
use App\Domains\Resume\Models\ResumeSection;
use App\Domains\Resume\Models\ResumeVersion;
use Illuminate\Support\Collection;

interface ResumeRepositoryInterface
{
    public function create(array $data): Resume;
    public function update(string $id, array $data): Resume;
    public function delete(string $id): bool;
    public function findById(string $id): ?Resume;
    public function getUserResumes(string $userId): Collection;
    
    public function updateOrCreateSection(string $resumeId, string $type, array $content, int $orderIndex): ResumeSection;
    public function createVersion(string $resumeId, int $versionNumber, array $resumeData): ResumeVersion;
}
