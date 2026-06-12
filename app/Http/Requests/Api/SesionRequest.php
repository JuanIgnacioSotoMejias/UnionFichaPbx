<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valida y sanitiza el payload del endpoint POST /api/sesion.
 *
 * Campos requeridos: usuario, evento.
 * Campos opcionales: extension, cola, nombre.
 */
class SesionRequest extends FormRequest
{
    /**
     * Las peticiones API siempre están autorizadas (la autenticación
     * se maneja en los middleware receptor.token y receptor.ips).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'usuario'   => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/'],
            'evento'    => ['required', Rule::in(['LOGIN', 'LOGOUT'])],
            'extension' => ['sometimes', 'string', 'regex:/^\d{3,6}$/'],
            'cola'      => ['sometimes', 'string', 'max:20', 'regex:/^[a-zA-Z0-9_-]+$/'],
            'nombre'    => ['sometimes', 'string', 'max:120'],
            'cedula'    => ['sometimes', 'nullable', 'string', 'max:30'],
            'hora_inicio_esperada' => ['sometimes', 'date_format:H:i:s'],
            'hora_fin_esperada'    => ['sometimes', 'date_format:H:i:s'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'usuario.required'  => 'El campo usuario es obligatorio.',
            'usuario.regex'     => 'El usuario solo puede contener letras, números, puntos, guiones y guiones bajos.',
            'evento.required'   => 'El campo evento es obligatorio.',
            'evento.in'         => 'El evento debe ser LOGIN o LOGOUT.',
            'extension.regex'   => 'La extensión debe contener entre 3 y 6 dígitos numéricos.',
            'cola.regex'        => 'La cola solo puede contener letras, números, guiones y guiones bajos.',
        ];
    }

    /**
     * Sanitiza los inputs antes de la validación.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'usuario' => trim((string) $this->input('usuario')),
            'evento'  => strtoupper(trim((string) $this->input('evento'))),
        ]);

        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => strip_tags(trim((string) $this->input('nombre'))),
            ]);
        }
    }
}
