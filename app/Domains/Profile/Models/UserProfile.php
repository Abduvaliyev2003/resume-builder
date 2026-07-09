<?php

namespace App\Domains\Profile\Models;

use App\Shared\Traits\HasUUID;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasUUID;

    protected $table = 'user_profiles';

    protected $fillable = [
        'user_id',
        'username',
        'phone',
        'date_of_birth',
        'country',
        'city',
        'bio',
        'avatar',
        'settings',
        'last_login_at',
        'password_changed_at',
    ];

    protected $casts = [
        'settings'       => 'array',
        'date_of_birth'  => 'date',
        'last_login_at'  => 'datetime',
        'password_changed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the avatar URL. Falls back to generated initials avatar.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user?->name ?? 'User')
            . '&background=2563eb&color=fff&size=128&bold=true';
    }

    /**
     * Get setting value with a default.
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return data_get($this->settings ?? [], $key, $default);
    }

    /**
     * Set a single setting key.
     */
    public function setSetting(string $key, mixed $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
    }

    /**
     * Get the user's preferred locale from settings.
     */
    public function getLocaleAttribute(): string
    {
        return $this->getSetting('language', config('app.locale', 'en'));
    }

    /**
     * Set the user's preferred locale in settings.
     */
    public function setLocaleAttribute(string $locale): void
    {
        $this->setSetting('language', $locale);
        $this->save();
    }
}
