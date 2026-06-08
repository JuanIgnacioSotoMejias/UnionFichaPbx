<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos para la creación de un nuevo usuario del sistema.
 */
class StoreUserRequest extends FormRequest
{
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
            'name'     => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\á\é\í\ó\ú\Á\É\Í\Ó\Ú\ñ\Ñ]+$/'],
            'cedula'   => ['required', 'string', 'unique:users,cedula', 'regex:/^[VEJ]-[0-9]+$/'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'string', 'in:admin,user'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'El nombre es obligatorio.',
            'name.regex'         => 'El nombre no puede contener números ni caracteres especiales.',
            'cedula.required'    => 'La cédula es obligatoria.',
            'cedula.unique'      => 'Esta cédula ya está registrada.',
            'cedula.regex'       => 'El formato de cédula es inválido (Ej: V-12345678, E-87654321, J-123456789).',
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.unique'       => 'Este correo electrónico ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
