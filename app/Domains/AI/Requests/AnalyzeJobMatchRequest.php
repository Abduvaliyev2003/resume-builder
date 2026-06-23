<?php

namespace App\Domains\AI\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyzeJobMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_title' => ['required', 'string', 'max:255'],
            'job_description' => ['required', 'string'],
        ];
    }
}
