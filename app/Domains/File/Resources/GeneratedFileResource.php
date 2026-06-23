<?php

namespace App\Domains\File\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneratedFileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'resume_id' => $this->resume_id,
            'file_type' => $this->file_type->value,
            'download_url' => route('resumes.download', ['token' => $this->download_token]),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
