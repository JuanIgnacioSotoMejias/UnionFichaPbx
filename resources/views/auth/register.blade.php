<x-guest-layout>
    <div class="mb-6 flex justify-center">
        <img src="/img/logo_ven911.png" alt="VEN-911" class="h-24 w-auto">
    </div>

    <h2 class="text-center text-lg font-black text-red-600 mb-8 uppercase italic">
        Registro de Administrador TI
    </h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-6 bg-red-50 p-4 rounded-xl border border-red-200">
            <x-input-label for="admin_code" :value="__('Código Maestro de Registro')" class="text-red-700 font-black" />
            <x-text-input id="admin_code" class="block mt-1 w-full border-red-400 focus:ring-red-600" type="password" name="admin_code" required />
            <p class="text-xs text-red-500 mt-2 italic font-semibold">* Solo para el administrador inicial.</p>
        </div>

        <div class="mt-4">
            <x-input-label for="name" :value="__('Nombre Completo')" />
            <x-text-input id="name" class="block mt-1 w-full border-gray-300 shadow-sm" type="text" name="name" :value="old('name')" required />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Correo Institucional')" />
            <x-text-input id="email" class="block mt-1 w-full border-gray-300 shadow-sm" type="email" name="email" :value="old('email')" required />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full border-gray-300 shadow-sm" type="password" name="password" required />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-gray-300 shadow-sm" type="password" name="password_confirmation" required />
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center bg-red-600 hover:bg-blue-900 py-4 font-bold transition-all shadow-lg">
                REGISTRAR ADMINISTRADOR
            </x-primary-button>
        </div>

        <div class="mt-6 text-center">
            <a class="text-sm text-gray-600 hover:text-blue-900 font-bold underline" href="{{ route('login') }}">
                ¿Ya tienes una cuenta? Inicia sesión
            </a>
        </div>
    </form>
</x-guest-layout>