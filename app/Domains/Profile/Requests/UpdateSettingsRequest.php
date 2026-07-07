<?php

namespace App\Domains\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'language'             => ['nullable', 'string', 'max:10'],
            'timezone'             => ['nullable', 'string', 'max:64'],
            'theme'                => ['nullable', Rule::in(['light', 'dark', 'system'])],
            'notify_email'         => ['nullable', 'boolean'],
            'notify_resume_updates'=> ['nullable', 'boolean'],
            'notify_security'      => ['nullable', 'boolean'],
            'notify_marketing'     => ['nullable', 'boolean'],
            'resume_visibility'    => ['nullable', Rule::in(['public', 'private', 'link_only'])],
        ];
    }
}
