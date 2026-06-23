<?php

namespace App\Domains\Analytics\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'action' => $this->action,
            'details' => $this->details,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
