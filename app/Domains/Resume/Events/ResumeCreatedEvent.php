<?php

namespace App\Domains\Resume\Events;

use App\Domains\Resume\Models\Resume;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResumeCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Resume $resume
    ) {}
}
