<?php

namespace App\Domains\File\Models;

use App\Shared\Traits\HasUUID;
use App\Shared\Enums\FileType;
use App\Domains\Resume\Models\Resume;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneratedFile extends Model
{
    use HasUUID;
    use SoftDeletes;

    protected $table = 'generated_files';

    protected $fillable = [
        'resume_id',
        'file_type',
        'file_path',
        'download_token',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'file_type' => FileType::class,
            'expires_at' => 'datetime',
        ];
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class, 'resume_id');
    }
}
