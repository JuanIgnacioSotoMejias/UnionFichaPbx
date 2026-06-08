<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('operadores.index') }}" class="text-gray-400 hover:text-gray-600 transition">← Volver</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar: {{ $operador->nombre_operador }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Formulario edición --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border p-6 space-y-4">
                <h3 class="font-medium text-gray-700 border-b pb-2">Datos del Operador</h3>

                <form method="POST" action="{{ route('operadores.update', $operador) }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Usuario Ficha</label>
                        <input type="text" name="ficha_username"
                               value="{{ old('ficha_username', $operador->ficha_username) }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('ficha_username') border-red-400 @enderror">
                        @error('ficha_username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Operador</label>
                        <input type="text" name="nombre_operador"
                               value="{{ old('nombre_operador', $operador->nombre_operador) }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('nombre_operador') border-red-400 @enderror">
                        @error('nombre_operador')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Extensión FreePBX</label>
                            <input type="text" name="extension"
                                   value="{{ old('extension', $operador->extension) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('extension') border-red-400 @enderror">
                            @error('extension')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cola PBX</label>
                            <input type="text" name="queue_name"
                                   value="{{ old('queue_name', $operador->queue_name) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('queue_name') border-red-400 @enderror">
                            @error('queue_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm px-5 py-2 rounded-lg transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>

                {{-- Estado actual y toggle manual --}}
                <div class="border-t pt-4">
                    <p class="text-sm text-gray-500 mb-2">Estado actual en cola:</p>
                    <div class="flex items-center gap-3">
                        @if($operador->is_active)
                            <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-700 text-sm font-semibold px-3 py-1 rounded-full">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> En Cola (Activo)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-500 text-sm font-semibold px-3 py-1 rounded-full">
                                <span class="w-2 h-2 bg-gray-400 rounded-full"></span> Inactivo
                            </span>
                        @endif

                        <form method="POST" action="{{ route('operadores.toggle', $operador) }}">
                            @csrf
                            <button type="submit"
                                    class="text-sm {{ $operador->is_active ? 'text-orange-500 hover:text-orange-700' : 'text-green-600 hover:text-green-800' }} underline">
                                {{ $operador->is_active ? '→ Desactivar manualmente' : '→ Activar manualmente' }}
                            </button>
                        </form>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        El toggle manual solo modifica el estado en BD, no envía comando AMI a FreePBX.
                    </p>
                </div>
            </div>

            {{-- Historial de accesos --}}
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="px-5 py-4 border-b">
                    <h3 class="font-medium text-gray-700">Historial de Accesos</h3>
                </div>
                <ul class="divide-y divide-gray-100 text-sm max-h-96 overflow-y-auto">
                    @forelse($historial as $h)
                    <li class="px-4 py-2.5 flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 font-medium
                            {{ $h->evento === 'LOGIN' ? 'text-green-600' : ($h->evento === 'LOGOUT' ? 'text-orange-500' : 'text-red-600') }}">
                            {{ $h->evento === 'LOGIN' ? '▶' : ($h->evento === 'LOGOUT' ? '■' : '✕') }}
                            {{ $h->evento }}
                        </span>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($h->created_at)->format('d/m H:i') }}</p>
                            @if($h->origen_ip)
                                <p class="text-xs text-gray-300 font-mono">{{ $h->origen_ip }}</p>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-6 text-center text-gray-400 text-xs">Sin historial registrado.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
