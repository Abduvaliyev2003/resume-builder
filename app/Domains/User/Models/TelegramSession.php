<?php

namespace App\Domains\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelegramSession extends Model
{
    protected $table = 'telegram_sessions';

    protected $fillable = [
        'user_id',
        'telegram_id',
        'telegram_username',
        'telegram_first_name',
        'telegram_last_name',
        'last_login_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
