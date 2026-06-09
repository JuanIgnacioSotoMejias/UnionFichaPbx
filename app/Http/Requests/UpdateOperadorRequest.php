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
            'extensiones'   => 'nullable|array',
            'extensiones.*' => 'exists:extensions,numero',
            'grupo_horario' => 'nullable|in:1,2',
            'horario_turno' => 'nullable|string|max:50',
            'horario_comida' => 'nullable|string|max:50',
            'horario_descanso' => 'nullable|string|max:50',
            'is_active'     => 'required|boolean',
        ];
    }
}
