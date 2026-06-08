<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Cabecera --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestión de Personal</h2>
                <p class="text-xs text-slate-400 mt-1">Administra los grupos horarios y extensiones de los operadores registrados.</p>
            </div>
        </div>

        {{-- Mensajes Flash --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabla de Operadores --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                        <tr class="border-b border-slate-800">
                            <th class="px-6 py-4 font-bold">Datos del Operador</th>
                            <th class="px-6 py-4 font-bold text-center">Ficha Username</th>
                            <th class="px-6 py-4 text-center font-bold">Grupo Horario</th>
                            <th class="px-6 py-4 text-center font-bold">Extensión</th>
                            <th class="px-6 py-4 text-center font-bold">Estado</th>
                            <th class="px-6 py-4 text-right font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($operadores as $op)
                        <tr class="hover:bg-slate-50 transition even:bg-slate-50/50 odd:bg-white" x-data="{ editing: false }">
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-800">{{ $op->nombre_operador }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-mono text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $op->ficha_username }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold {{ $op->grupo_horario == 1 ? 'text-blue-600' : 'text-slate-600' }}">Grupo {{ $op->grupo_horario ?? 'No Asignado' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-1 border border-slate-200 rounded">{{ $op->extension === '0000' ? 'Sin Asignar' : $op->extension }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest {{ $op->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $op->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="editing = true" class="text-blue-500 hover:text-blue-700 p-2 rounded hover:bg-blue-50 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                
                                {{-- Modal de Edición (Oculto) --}}
                                <div x-show="editing" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="editing" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" @click="editing = false"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div x-show="editing" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                                            <form action="{{ route('operadores.update', $op->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                            <h3 class="text-lg leading-6 font-black text-slate-800 uppercase tracking-tight" id="modal-title">
                                                                Editar Operador: {{ $op->nombre_operador }}
                                                            </h3>
                                                            <div class="mt-4 space-y-4">
                                                                {{-- Extensión --}}
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Asignar Extensión</label>
                                                                    <select name="extension" class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                                                                        <option value="0000" {{ $op->extension === '0000' ? 'selected' : '' }}>Sin Asignar (0000)</option>
                                                                        @if($op->extension !== '0000')
                                                                            <option value="{{ $op->extension }}" selected>{{ $op->extension }} (Actual)</option>
                                                                        @endif
                                                                        @foreach($extensionesLibres as $extLibre)
                                                                            <option value="{{ $extLibre->numero }}">{{ $extLibre->numero }} {{ $extLibre->grupo_horario ? '— Grupo '.$extLibre->grupo_horario : '' }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <p class="text-[10px] text-blue-500 mt-1 font-bold">ℹ️ El grupo horario se hereda automáticamente de la extensión seleccionada.</p>
                                                                </div>
                                                                {{-- Grupo Horario --}}
                                                                <div>
                                                                    <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest mt-4">Grupo Horario</label>
                                                                    <select name="grupo_horario" class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm font-bold text-slate-700">
                                                                        <option value="" {{ empty($op->grupo_horario) ? 'selected' : '' }}>Sin Grupo</option>
                                                                        <option value="1" {{ $op->grupo_horario == 1 ? 'selected' : '' }}>Grupo 1 (08:30 a 20:30)</option>
                                                                        <option value="2" {{ $op->grupo_horario == 2 ? 'selected' : '' }}>Grupo 2 (20:30 a 08:30)</option>
                                                                    </select>
                                                                </div>
                                                                {{-- Estado (Activo/Inactivo) --}}
                                                                <div>
                                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estado en Turno</label>
                                                                    <select name="is_active" class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                                                                        <option value="1" {{ $op->is_active ? 'selected' : '' }}>Activo</option>
                                                                        <option value="0" {{ !$op->is_active ? 'selected' : '' }}>Inactivo</option>
                                                                    </select>
                                                                    <p class="text-[10px] text-slate-400 mt-1">* Inactivar a un operador lo remueve del dashboard de turno actual.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-lg shadow-blue-200 px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition uppercase tracking-widest font-bold">
                                                        Guardar Cambios
                                                    </button>
                                                    <button type="button" @click="editing = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest font-bold">
                                                        Cancelar
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 italic text-sm">
                                No hay operadores registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($operadores->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $operadores->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
