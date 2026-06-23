<?php

namespace App\Domains\Resume\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeSection extends Model
{
    use HasUUID;

    protected $table = 'resume_sections';

    protected $fillable = [
        'resume_id',
        'section_type',
        'content',
        'order_index',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'order_index' => 'integer',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
