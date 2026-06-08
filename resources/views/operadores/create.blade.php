<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('operadores.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                ← Volver
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo Operador</h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl bg-white rounded-xl shadow-sm border p-6 space-y-5">

            <p class="text-sm text-gray-500">
                Registra la vinculación entre el <strong>usuario del sistema Ficha</strong> y
                la <strong>extensión FreePBX</strong> del operador.
            </p>

            <form method="POST" action="{{ route('operadores.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Usuario Ficha <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="ficha_username" value="{{ old('ficha_username') }}"
                           placeholder="ej: jperez"
                           class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('ficha_username') border-red-400 @enderror">
                    @error('ficha_username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-400 mt-1">Campo <code>usuario</code> de la tabla <code>ficha_ven_911.usuarios</code></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nombre del Operador <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nombre_operador" value="{{ old('nombre_operador') }}"
                           placeholder="ej: Juan Pérez"
                           class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('nombre_operador') border-red-400 @enderror">
                    @error('nombre_operador')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Extensión FreePBX <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="extension" value="{{ old('extension') }}"
                               placeholder="ej: 8001"
                               class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('extension') border-red-400 @enderror">
                        @error('extension')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Cola PBX <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="queue_name" value="{{ old('queue_name', '0911') }}"
                               placeholder="ej: 0911"
                               class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('queue_name') border-red-400 @enderror">
                        @error('queue_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2 rounded-lg transition">
                        Registrar Operador
                    </button>
                    <a href="{{ route('operadores.index') }}" class="text-sm text-gray-500 hover:underline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
