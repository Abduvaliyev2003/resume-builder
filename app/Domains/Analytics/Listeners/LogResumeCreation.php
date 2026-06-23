<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\Resume\Events\ResumeCreatedEvent;
use App\Domains\Analytics\Services\AnalyticsService;

class LogResumeCreation
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function handle(ResumeCreatedEvent $event): void
    {
        $this->analyticsService->track(
            $event->resume->user_id,
            'create_resume',
            ['resume_id' => $event->resume->id, 'title' => $event->resume->title]
        );
    }
}
