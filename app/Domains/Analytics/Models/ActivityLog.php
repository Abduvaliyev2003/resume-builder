<?php

namespace App\Domains\Analytics\Models;

use App\Shared\Traits\HasUUID;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasUUID;

    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'details',
    ];

    protected function casts(): array
    {
        return [
            'details' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
