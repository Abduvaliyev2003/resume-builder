<?php

namespace App\Domains\File\Events;

use App\Domains\File\Models\GeneratedFile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResumeExportedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly GeneratedFile $generatedFile
    ) {}
}
