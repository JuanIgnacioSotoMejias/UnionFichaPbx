<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Operadores</h2>
            <a href="{{ route('operadores.create') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                + Nuevo Operador
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">

        {{-- Mensajes flash --}}
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider border-b">
                    <tr>
                        <th class="px-5 py-3 text-left">Operador</th>
                        <th class="px-5 py-3 text-left">Usuario Ficha</th>
                        <th class="px-5 py-3 text-left">Extensión</th>
                        <th class="px-5 py-3 text-left">Cola PBX</th>
                        <th class="px-5 py-3 text-center">Estado</th>
                        <th class="px-5 py-3 text-center">Eventos</th>
                        <th class="px-5 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($operadores as $op)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $op->nombre_operador }}</td>
                        <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $op->ficha_username }}</td>
                        <td class="px-5 py-3">
                            <span class="bg-indigo-50 text-indigo-700 font-mono text-xs px-2 py-0.5 rounded">{{ $op->extension }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $op->queue_name }}</td>
                        <td class="px-5 py-3 text-center">
                            @if($op->is_active)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> En Cola
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Inactivo
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-center text-gray-500">{{ $op->historial_count }}</td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('operadores.edit', $op) }}"
                                   class="text-xs text-indigo-600 hover:underline">Editar</a>

                                {{-- Toggle manual de estado --}}
                                <form method="POST" action="{{ route('operadores.toggle', $op) }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs {{ $op->is_active ? 'text-orange-500 hover:text-orange-700' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $op->is_active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('operadores.destroy', $op) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ $op->nombre_operador }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                            No hay operadores registrados.
                            <a href="{{ route('operadores.create') }}" class="text-indigo-600 hover:underline ml-1">Registrar el primero →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($operadores->hasPages())
            <div class="px-5 py-4 border-t">
                {{ $operadores->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
