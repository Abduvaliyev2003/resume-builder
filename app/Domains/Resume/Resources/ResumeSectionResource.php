<?php

namespace App\Domains\Resume\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumeSectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section_type' => $this->section_type,
            'content' => $this->content,
            'order_index' => $this->order_index,
        ];
    }
}
