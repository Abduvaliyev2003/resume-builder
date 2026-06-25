<?php

namespace App\Domains\File\Jobs;

use App\Domains\File\Actions\ExportResumeAction;
use App\Shared\Enums\FileType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateResumePdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $resumeId
    ) {}

    public function handle(ExportResumeAction $action): void
    {
        $action->execute($this->resumeId, FileType::PDF);
    }
}
