<?php

namespace App\Domains\File\Services;

use App\Domains\File\Models\GeneratedFile;
use App\Domains\File\Actions\ExportResumeAction;
use App\Domains\File\Repositories\FileRepositoryInterface;
use App\Shared\Enums\FileType;

class FileService
{
    public function __construct(
        protected FileRepositoryInterface $fileRepository,
        protected ExportResumeAction $exportAction
    ) {}

    public function exportResume(string $resumeId, FileType $fileType): GeneratedFile
    {
        return $this->exportAction->execute($resumeId, $fileType);
    }

    public function findFileByToken(string $token): ?GeneratedFile
    {
        return $this->fileRepository->findByToken($token);
    }
}
