<?php

namespace App\Domains\File\Repositories;

use App\Domains\File\Models\GeneratedFile;
use Illuminate\Support\Collection;

interface FileRepositoryInterface
{
    public function create(array $data): GeneratedFile;
    public function findByToken(string $token): ?GeneratedFile;
    public function getFilesForResume(string $resumeId): Collection;
}
