<?php

namespace App\Domains\File\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Domains\File\Actions\ExportResumeAction;
use App\Shared\Enums\FileType;

class ProcessResumePDFExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $resumeId,
        public readonly FileType $fileType
    ) {}

    public function handle(ExportResumeAction $action): void
    {
        $action->execute($this->resumeId, $this->fileType);
    }
}
