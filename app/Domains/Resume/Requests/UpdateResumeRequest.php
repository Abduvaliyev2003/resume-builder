<?php

namespace App\Domains\Resume\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'template_id' => ['nullable', 'uuid', 'exists:templates,id'],
            'sections' => ['nullable', 'array'],
            'sections.*.section_type' => ['required', 'string'],
            'sections.*.content' => ['required', 'array'],
            'sections.*.order_index' => ['nullable', 'integer'],
        ];
    }
}
