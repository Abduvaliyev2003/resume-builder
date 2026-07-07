<?php

namespace App\Domains\Profile\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password'    => ['required', 'string'],
            'confirm_text'=> ['required', 'string', 'in:DELETE'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm_text.in' => 'You must type DELETE to confirm account deletion.',
        ];
    }
}
