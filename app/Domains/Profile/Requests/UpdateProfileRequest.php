<?php

namespace App\Domains\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'name'          => ['required', 'string', 'max:255'],
            'username'      => ['nullable', 'string', 'max:50', 'alpha_dash', Rule::unique('user_profiles', 'username')->ignore($userId, 'user_id')],
            'phone'         => ['nullable', 'string', 'max:30'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender'        => ['nullable', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'job_title'     => ['nullable', 'string', 'max:100'],
            'company'       => ['nullable', 'string', 'max:100'],
            'country'       => ['nullable', 'string', 'max:100'],
            'city'          => ['nullable', 'string', 'max:100'],
            'website'       => ['nullable', 'url', 'max:255'],
            'bio'           => ['nullable', 'string', 'max:1000'],
            'avatar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'Username may only contain letters, numbers, dashes, and underscores.',
            'username.unique'     => 'This username is already taken.',
            'website.url'         => 'Please enter a valid URL (e.g., https://example.com).',
            'avatar.max'          => 'Profile photo must not exceed 2MB.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
        ];
    }
}
