<?php

namespace App\Domains\Resume\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumeVersionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'version_number' => $this->version_number,
            'resume_data' => $this->resume_data,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
