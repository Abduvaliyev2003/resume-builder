<?php

namespace App\Domains\File\Repositories;

use App\Domains\File\Models\GeneratedFile;
use Illuminate\Support\Collection;

class FileRepository implements FileRepositoryInterface
{
    public function create(array $data): GeneratedFile
    {
        return GeneratedFile::create($data);
    }

    public function findByToken(string $token): ?GeneratedFile
    {
        return GeneratedFile::where('download_token', $token)->first();
    }

    public function getFilesForResume(string $resumeId): Collection
    {
        return GeneratedFile::where('resume_id', $resumeId)->get();
    }
}
