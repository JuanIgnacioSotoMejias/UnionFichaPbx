<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExtensionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'numero'      => 'required|string|unique:extensions,numero,' . $this->route('extension')->id,
            'descripcion' => 'nullable|string',
            'estado'      => 'required|in:libre,en_uso,inactiva',
            'grupo_horario' => 'nullable|in:1,2',
        ];
    }
}
