<?php

namespace App\Domains\User\Models;

use App\Shared\Traits\HasUUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Domains\Resume\Models\Resume;
use App\Domains\Analytics\Models\ActivityLog;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Domains\Profile\Models\UserProfile;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasUUID, HasRoles;

    protected $table = 'users';

    protected static function newFactory(): \Database\Factories\UserFactory
    {
        return \Database\Factories\UserFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class, 'user_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'user_id');
    }

    public function telegramSession(): HasOne
    {
        return $this->hasOne(TelegramSession::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function emailVerificationCodes(): HasMany
    {
        return $this->hasMany(EmailVerificationCode::class, 'user_id');
    }

    public function sendEmailVerificationNotification(): void
    {
        app(\App\Domains\User\Services\EmailVerificationService::class)->generateCode($this);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin'
            && $this->hasRole('super_admin');
    }
}
