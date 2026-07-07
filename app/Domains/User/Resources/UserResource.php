<?php

namespace App\Domains\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'username'   => $this->profile?->username,
            'avatar_url' => $this->profile?->avatar_url,
            'job_title'  => $this->profile?->job_title,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
