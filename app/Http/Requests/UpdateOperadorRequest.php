<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOperadorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'extension'     => 'required|string',
            'grupo_horario' => 'nullable|in:1,2',
            'is_active'     => 'required|boolean',
        ];
    }
}
