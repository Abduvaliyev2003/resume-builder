<?php

namespace App\Domains\Resume\Models;

use App\Shared\Traits\HasUUID;
use App\Domains\User\Models\User;
use App\Domains\Template\Models\Template;
use App\Domains\AI\Models\AIReview;
use App\Domains\File\Models\GeneratedFile;
use App\Domains\Analytics\Models\JobTarget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resume extends Model
{
    use HasUUID;

    protected $table = 'resumes';

    protected $fillable = [
        'user_id',
        'template_id',
        'title',
        'score',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ResumeSection::class, 'resume_id')->orderBy('order_index');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ResumeVersion::class, 'resume_id')->orderBy('version_number', 'desc');
    }

    public function generatedFiles(): HasMany
    {
        return $this->hasMany(GeneratedFile::class, 'resume_id');
    }

    public function aiReviews(): HasMany
    {
        return $this->hasMany(AIReview::class, 'resume_id');
    }

    public function jobTargets(): HasMany
    {
        return $this->hasMany(JobTarget::class, 'resume_id');
    }
}
