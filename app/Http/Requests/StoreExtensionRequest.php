<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtensionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero'      => 'required|string|unique:extensions,numero',
            'descripcion' => 'nullable|string',
            'grupo_horario' => 'nullable|in:1,2',
        ];
    }
}
