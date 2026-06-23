<?php

namespace App\Domains\File\Requests;

use App\Shared\Enums\FileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ExportResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file_type' => ['required', new Enum(FileType::class)],
        ];
    }
}
