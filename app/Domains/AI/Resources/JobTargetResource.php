<?php

namespace App\Domains\AI\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobTargetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'resume_id' => $this->resume_id,
            'job_title' => $this->job_title,
            'job_description' => $this->job_description,
            'match_score' => $this->match_score,
            'analysis_data' => $this->analysis_data,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
