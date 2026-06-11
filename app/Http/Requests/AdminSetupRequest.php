<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSetupRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a hacer esta solicitud.
     * Al ser una ruta de setup inicial, siempre se permite.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para el registro del administrador inicial.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'min:3', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'setup_code' => ['required', 'string'],
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
            'name.required'       => 'El nombre completo es obligatorio.',
            'name.min'            => 'El nombre debe tener al menos 3 caracteres.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.email'         => 'Ingresa un correo electrónico válido.',
            'email.unique'        => 'Este correo ya está registrado en el sistema.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'  => 'La confirmación de contraseña no coincide.',
            'setup_code.required' => 'El código de configuración es obligatorio.',
        ];
    }

    /**
     * Atributos personalizados para los mensajes de error.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'       => 'nombre completo',
            'email'      => 'correo electrónico',
            'password'   => 'contraseña',
            'setup_code' => 'código de configuración',
        ];
    }
}
