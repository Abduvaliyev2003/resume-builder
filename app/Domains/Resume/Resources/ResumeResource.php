<?php

namespace App\Domains\Resume\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domains\Template\Resources\TemplateResource;

class ResumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'title' => $this->title,
            'score' => $this->score,
            'template' => new TemplateResource($this->whenLoaded('template')),
            'sections' => ResumeSectionResource::collection($this->whenLoaded('sections')),
            'versions' => ResumeVersionResource::collection($this->whenLoaded('versions')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
