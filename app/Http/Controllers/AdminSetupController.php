<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminSetupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminSetupController extends Controller
{
    /**
     * Muestra el formulario de registro del administrador inicial.
     *
     * Regla de negocio: si ya existe al menos un usuario en la
     * base de datos, se redirige al login tradicional con un 403.
     */
    public function showRegistrationForm(): View|RedirectResponse
    {
        // Si ya existen usuarios, bloquear el acceso
        if (User::count() > 0) {
            abort(403, 'El sistema ya fue inicializado. No se permite el acceso a esta interfaz.');
        }

        return view('auth.admin-setup');
    }

    /**
     * Procesa el registro del primer administrador del sistema.
     *
     * Valida el código de configuración contra la variable de
     * entorno ADMIN_SETUP_CODE y crea el usuario con rol 'admin'.
     */
    public function store(AdminSetupRequest $request): RedirectResponse
    {
        // Doble verificación: si ya existen usuarios, abortar
        if (User::count() > 0) {
            abort(403, 'El sistema ya fue inicializado. No se permite crear cuentas adicionales.');
        }

        // Validar el código de configuración contra el .env
        $envCode = config('app.admin_setup_code');

        if (empty($envCode) || $request->input('setup_code') !== $envCode) {
            return back()
                ->withInput($request->except('password', 'password_confirmation', 'setup_code'))
                ->withErrors([
                    'setup_code' => 'El código de configuración no es válido.',
                ]);
        }

        // Crear el administrador inicial
        $user = User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => User::ROLE_ADMIN,
            'is_active' => true,
        ]);

        // Autenticar al nuevo administrador
        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Sistema inicializado correctamente. Bienvenido, Administrador.');
    }
}
