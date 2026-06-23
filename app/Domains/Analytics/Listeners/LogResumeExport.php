<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\File\Events\ResumeExportedEvent;
use App\Domains\Analytics\Services\AnalyticsService;

class LogResumeExport
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function handle(ResumeExportedEvent $event): void
    {
        $file = $event->generatedFile;
        $resume = $file->resume;

        if ($resume) {
            $this->analyticsService->track(
                $resume->user_id,
                'download',
                [
                    'resume_id' => $resume->id,
                    'file_id' => $file->id,
                    'file_type' => $file->file_type->value
                ]
            );
        }
    }
}
