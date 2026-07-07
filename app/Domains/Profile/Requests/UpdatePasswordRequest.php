<?php

namespace App\Domains\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.confirmed'   => 'The new passwords do not match.',
            'new_password.different'   => 'New password must be different from your current password.',
            'new_password.min'         => 'New password must be at least 8 characters.',
        ];
    }
}
