<?php

namespace App\Domains\Analytics\Models;

use App\Shared\Traits\HasUUID;
use App\Domains\Resume\Models\Resume;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTarget extends Model
{
    use HasUUID;

    protected $table = 'job_targets';

    protected $fillable = [
        'resume_id',
        'job_title',
        'job_description',
        'match_score',
        'analysis_data',
    ];

    protected function casts(): array
    {
        return [
            'match_score' => 'integer',
            'analysis_data' => 'array',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
