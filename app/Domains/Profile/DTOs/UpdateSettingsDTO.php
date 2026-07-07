<?php

namespace App\Domains\Profile\DTOs;

use Illuminate\Http\Request;

class UpdateSettingsDTO
{
    public function __construct(
        // Account Settings
        public readonly ?string $language,
        public readonly ?string $timezone,
        public readonly ?string $theme,
        // Notification Settings
        public readonly bool $notifyEmail,
        public readonly bool $notifyResumeUpdates,
        public readonly bool $notifySecurity,
        public readonly bool $notifyMarketing,
        // Resume Settings
        public readonly ?string $resumeVisibility,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            language:            $request->input('language', 'en'),
            timezone:            $request->input('timezone', 'UTC'),
            theme:               $request->input('theme', 'system'),
            notifyEmail:         (bool) $request->input('notify_email', false),
            notifyResumeUpdates: (bool) $request->input('notify_resume_updates', false),
            notifySecurity:      (bool) $request->input('notify_security', true),
            notifyMarketing:     (bool) $request->input('notify_marketing', false),
            resumeVisibility:    $request->input('resume_visibility', 'private'),
        );
    }

    public function toSettingsArray(): array
    {
        return [
            'language'  => $this->language,
            'timezone'  => $this->timezone,
            'theme'     => $this->theme,
            'notifications' => [
                'email'          => $this->notifyEmail,
                'resume_updates' => $this->notifyResumeUpdates,
                'security'       => $this->notifySecurity,
                'marketing'      => $this->notifyMarketing,
            ],
            'resume_visibility' => $this->resumeVisibility,
        ];
    }
}
