<?php

namespace App\Domains\AI\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckGrammarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => ['required', 'string'],
        ];
    }
}
