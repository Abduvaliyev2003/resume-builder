<?php

namespace App\Domains\Template\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'style' => $this->style->value,
            'description' => $this->description,
            'structure' => $this->structure,
            'is_active' => $this->is_active,
        ];
    }
}
