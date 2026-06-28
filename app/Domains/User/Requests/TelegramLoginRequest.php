<?php

namespace App\Domains\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TelegramLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],

            'telegram_id' => [
                'required',
                'integer',
            ],

            'telegram_username' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telegram_first_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'telegram_last_name' => [
                'nullable',
                'string',
                'max:255',
            ],

        ];
    }
}
