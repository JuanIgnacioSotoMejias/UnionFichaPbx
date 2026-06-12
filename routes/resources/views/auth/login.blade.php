<x-guest-layout>
    <div class="mb-6 flex justify-center">
        <img src="/img/logo_ven911.png" alt="VEN-911" class="h-24 w-auto drop-shadow-md">
    </div>

    <h1 class="text-center text-4xl font-black text-red-600 mb-8 uppercase tracking-tighter leading-none">
        PBX RECEPTOR
    </h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Correo Institucional')" class="font-black text-gray-800" />
            <x-text-input id="email" class="block mt-1 w-full border-red-200 focus:border-red-600 focus:ring-red-600" type="email" name="email" :value="old('email')" required autofocus />
        </div>

        <div class="mt-6">
            <x-input-label for="password" :value="__('Contraseña')" class="font-black text-gray-800" />
            <x-text-input id="password" class="block mt-1 w-full border-red-200 focus:border-red-600 focus:ring-red-600" type="password" name="password" required />
        </div>

        <div class="flex items-center justify-between mt-6">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="rounded border-red-300 text-red-600 focus:ring-red-600">
                <span class="ms-2 text-sm font-bold text-gray-600">Recordarme</span>
            </label>
            <a class="text-sm text-red-700 hover:text-red-900 font-black underline" href="{{ route('password.request') }}">
                ¿Olvidaste tu clave?
            </a>
        </div>

        <div class="mt-8">
            <x-primary-button class="w-full justify-center bg-red-600 hover:bg-red-700 py-4 text-xl font-black transition-all shadow-lg active:transform active:scale-95">
                INICIAR SESIÓN
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>