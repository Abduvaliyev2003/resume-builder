<?php

namespace App\Domains\Resume\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeVersion extends Model
{
    use HasUUID;

    protected $table = 'resume_versions';

    public $timestamps = false;

    protected $fillable = [
        'resume_id',
        'version_number',
        'resume_data',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'resume_data' => 'array',
            'version_number' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
