<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>Inicialización del Sistema — {{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>[x-cloak] { display: none !important; }</style>
    </head>
    <body class="font-sans antialiased bg-slate-900 min-h-screen">

        {{-- ═══════════════════════════════════════════════════════════════
             CABECERA OSCURA CORPORATIVA
        ═══════════════════════════════════════════════════════════════ --}}
        <header class="bg-slate-950 border-b border-slate-800">
            <div class="max-w-2xl mx-auto px-6 py-5 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-600/20">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white font-black text-lg uppercase tracking-tighter">PBX Receptor</h1>
                        <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">Configuración Inicial</p>
                    </div>
                </div>
                <span class="text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 px-3 py-1.5 rounded-lg font-bold uppercase tracking-widest">
                    Setup Mode
                </span>
            </div>
        </header>

        {{-- ═══════════════════════════════════════════════════════════════
             CONTENIDO PRINCIPAL
        ═══════════════════════════════════════════════════════════════ --}}
        <main class="max-w-xl mx-auto px-6 py-12">

            {{-- Aviso informativo --}}
            <div class="mb-8 bg-slate-800/50 border border-slate-700 rounded-2xl p-5 flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-500/10 rounded-full flex items-center justify-center mt-0.5">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-black text-white uppercase tracking-tight">Interfaz de Inicialización</p>
                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                        Esta pantalla solo está disponible cuando el sistema no tiene usuarios registrados.
                        Una vez creado el primer administrador, esta interfaz se desactivará automáticamente
                        y no será accesible nuevamente.
                    </p>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════════════════
                 TARJETA DEL FORMULARIO
            ═══════════════════════════════════════════════════════════ --}}
            <div class="bg-white rounded-2xl shadow-2xl shadow-black/20 overflow-hidden border border-slate-200">

                {{-- Encabezado de la tarjeta --}}
                <div class="bg-slate-900 px-8 py-6 border-b-4 border-red-600">
                    <h2 class="text-xl font-black text-white uppercase tracking-tighter">
                        Registrar Administrador
                    </h2>
                    <p class="text-slate-400 text-xs mt-1 font-medium">
                        Completa todos los campos para crear la cuenta principal del sistema.
                    </p>
                </div>

                {{-- Formulario --}}
                <form
                    method="POST"
                    action="{{ route('admin.setup.store') }}"
                    novalidate
                    class="p-8 space-y-6"
                    x-data="{
                        email: '{{ old('email') }}',
                        emailTouched: false,
                        get emailFormatError() {
                            if (!this.emailTouched || this.email.trim() === '') return '';
                            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            return regex.test(this.email) ? '' : 'Ingresa un correo electrónico con formato válido.';
                        }
                    }"
                >
                    @csrf

                    {{-- ── NOMBRE COMPLETO ─────────────────────────────── --}}
                    <div>
                        <label for="name" class="block text-sm font-black text-gray-800 uppercase tracking-tight mb-1.5">
                            Nombre Completo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 {{ $errors->has('name') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                autofocus
                                placeholder="Ej: Carlos Mendoza"
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border shadow-sm text-sm transition-all duration-200
                                    {{ $errors->has('name')
                                        ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                                        : 'border-slate-300 focus:border-slate-600 focus:ring-slate-600' }}"
                            />
                        </div>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── CORREO INSTITUCIONAL ─────────────────────────── --}}
                    <div>
                        <label for="email" class="block text-sm font-black text-gray-800 uppercase tracking-tight mb-1.5">
                            Correo Institucional
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 transition-colors duration-200" :class="emailFormatError || {{ $errors->has('email') ? 'true' : 'false' }} ? 'text-red-500' : 'text-slate-400'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                x-model="email"
                                @blur="emailTouched = true"
                                placeholder="admin@ven911.gob.ve"
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border shadow-sm text-sm transition-all duration-200
                                    {{ $errors->has('email')
                                        ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                                        : 'border-slate-300 focus:border-slate-600 focus:ring-slate-600' }}"
                                :class="emailFormatError && !{{ $errors->has('email') ? 'true' : 'false' }} ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : ''"
                            />
                        </div>
                        {{-- Error Alpine.js en tiempo real --}}
                        <span
                            x-show="emailFormatError"
                            x-cloak
                            class="text-red-500 text-xs mt-1 block"
                            x-text="emailFormatError"
                        ></span>
                        {{-- Error del backend --}}
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── CONTRASEÑA ───────────────────────────────────── --}}
                    <div>
                        <label for="password" class="block text-sm font-black text-gray-800 uppercase tracking-tight mb-1.5">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 {{ $errors->has('password') ? 'text-red-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Mínimo 8 caracteres"
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border shadow-sm text-sm transition-all duration-200
                                    {{ $errors->has('password')
                                        ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                                        : 'border-slate-300 focus:border-slate-600 focus:ring-slate-600' }}"
                            />
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- ── CONFIRMAR CONTRASEÑA ─────────────────────────── --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-black text-gray-800 uppercase tracking-tight mb-1.5">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Repite tu contraseña"
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border border-slate-300 shadow-sm text-sm focus:border-slate-600 focus:ring-slate-600 transition-all duration-200"
                            />
                        </div>
                    </div>

                    {{-- ── SEPARADOR VISUAL ─────────────────────────────── --}}
                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-slate-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="bg-white px-3 text-slate-400 font-bold tracking-widest">Verificación de Seguridad</span>
                        </div>
                    </div>

                    {{-- ── CÓDIGO DE CONFIGURACIÓN ──────────────────────── --}}
                    <div>
                        <label for="setup_code" class="block text-sm font-black text-gray-800 uppercase tracking-tight mb-1.5">
                            Código de Configuración
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 {{ $errors->has('setup_code') ? 'text-red-500' : 'text-amber-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <input
                                id="setup_code"
                                type="password"
                                name="setup_code"
                                placeholder="Código proporcionado por el equipo técnico"
                                class="block w-full pl-11 pr-4 py-3 rounded-xl border shadow-sm text-sm font-mono transition-all duration-200
                                    {{ $errors->has('setup_code')
                                        ? 'border-red-500 focus:ring-red-500 focus:border-red-500'
                                        : 'border-amber-300 bg-amber-50/50 focus:border-amber-500 focus:ring-amber-500' }}"
                            />
                        </div>
                        @error('setup_code')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                        <p class="mt-1.5 text-xs text-slate-400">
                            Este código fue definido en la configuración del servidor (.env) por el equipo de despliegue.
                        </p>
                    </div>

                    {{-- ── BOTÓN DE ACCIÓN ──────────────────────────────── --}}
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full flex items-center justify-center gap-3 bg-red-600 hover:bg-red-700 text-white font-black uppercase tracking-tight text-sm py-4 px-6 rounded-xl shadow-lg shadow-red-600/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Inicializar Sistema y Crear Administrador
                        </button>
                    </div>
                </form>
            </div>

            {{-- Pie informativo --}}
            <p class="text-center mt-8 text-xs text-slate-600 font-medium">
                VEN-911 &copy; {{ date('Y') }} — PBX Receptor — Módulo de Inicialización Segura
            </p>
        </main>

    </body>
</html>
