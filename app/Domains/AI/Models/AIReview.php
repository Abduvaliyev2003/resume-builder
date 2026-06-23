<?php

namespace App\Domains\AI\Models;

use App\Shared\Traits\HasUUID;
use App\Domains\Resume\Models\Resume;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIReview extends Model
{
    use HasUUID;

    protected $table = 'ai_reviews';

    protected $fillable = [
        'resume_id',
        'review_type',
        'score',
        'feedback_data',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'feedback_data' => 'array',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
