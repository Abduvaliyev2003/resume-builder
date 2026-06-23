<?php

namespace App\Domains\Template\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Resume\Models\Resume;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Template extends Model
{
    use HasUUID;

    protected $table = 'templates';

    protected $fillable = [
        'name',
        'style',
        'description',
        'structure',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'style' => 'string',
            'structure' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class, 'template_id');
    }
}
