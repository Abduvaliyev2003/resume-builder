<?php

namespace App\Domains\Analytics\Listeners;

use App\Domains\User\Events\UserRegisteredEvent;
use App\Domains\Analytics\Services\AnalyticsService;

class LogUserRegistration
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    public function handle(UserRegisteredEvent $event): void
    {
        $this->analyticsService->track(
            $event->user->id,
            'register',
            ['email' => $event->user->email, 'name' => $event->user->name]
        );
    }
}
