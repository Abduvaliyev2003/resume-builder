<?php

namespace App\Domains\AI\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'resume_id' => $this->resume_id,
            'review_type' => $this->review_type,
            'score' => $this->score,
            'feedback_data' => $this->feedback_data,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
