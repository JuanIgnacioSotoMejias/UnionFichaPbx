<table class="min-w-full text-left border-collapse">
    <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
        <tr class="border-b border-slate-800">
            <th class="px-6 py-4 font-bold">Datos del Operador</th>
            <th class="px-6 py-4 font-bold text-center">Ficha Username</th>
            <th class="px-6 py-4 text-center font-bold">Horario de Turno</th>
            <th class="px-6 py-4 text-center font-bold">Extensiones</th>
            <th class="px-6 py-4 text-center font-bold">Estado</th>
            @can('manage-system')
            <th class="px-6 py-4 text-right font-bold">Acciones</th>
            @endcan
        </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
        @forelse($operadores as $op)
        <tr class="hover:bg-slate-50 transition even:bg-slate-50/50 odd:bg-white" x-data="{ editing_{{ $op->id }}: false, toggling_{{ $op->id }}: false }">
            <td class="px-6 py-4">
                <p class="text-sm font-bold text-slate-800">{{ $op->nombre_operador }}</p>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="text-xs font-mono text-slate-500 bg-slate-100 px-2 py-1 rounded">{{ $op->ficha_username }}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex flex-col gap-1 items-center">
                    <span class="text-xs font-bold {{ $op->grupo_horario == 1 ? 'text-blue-600' : 'text-slate-600' }}">Grupo {{ $op->grupo_horario ?? 'No Asignado' }}</span>
                    @if($op->horario_turno)
                        <span class="text-[10px] text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">⏱️ {{ $op->horario_turno }}</span>
                    @endif
                    @if($op->horario_comida)
                        <span class="text-[10px] text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">🍔 {{ $op->horario_comida }}</span>
                    @endif
                    @if($op->horario_descanso)
                        <span class="text-[10px] text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">☕ {{ $op->horario_descanso }}</span>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <div class="flex flex-wrap gap-1 justify-center">
                    @forelse($op->extensiones as $extOp)
                        <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2 py-0.5 border border-slate-200 rounded">{{ $extOp->numero }}</span>
                    @empty
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 border border-slate-100 rounded">Sin Asignar</span>
                    @endforelse
                </div>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest {{ $op->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $op->is_active ? 'Activo' : 'Inactivo' }}
                </span>
            </td>
            @can('manage-system')
            <td class="px-6 py-4 text-right">
                <button @click="editing_{{ $op->id }} = true" class="text-blue-500 hover:text-blue-700 p-2 rounded hover:bg-blue-50 transition" title="Editar Operador">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </button>

                @if($op->is_active)
                <button @click="toggling_{{ $op->id }} = true" class="text-red-500 hover:text-red-700 p-2 rounded hover:bg-red-50 transition" title="Deshabilitar Operador">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </button>
                @else
                <button @click="toggling_{{ $op->id }} = true" class="text-emerald-500 hover:text-emerald-700 p-2 rounded hover:bg-emerald-50 transition" title="Habilitar Operador">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
                @endif

                {{-- Modal de Toggle Activo/Inactivo --}}
                <div x-show="toggling_{{ $op->id }}" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="toggling_{{ $op->id }}" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="toggling_{{ $op->id }} = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        <div x-show="toggling_{{ $op->id }}" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-200">
                            <form action="{{ route('operadores.toggleActivo', $op->id) }}" method="POST">
                                @csrf
                                <div class="bg-[#1e293b] px-6 py-4 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $op->is_active ? 'bg-red-500/20' : 'bg-green-500/20' }}">
                                            @if($op->is_active)
                                                <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                        <h3 class="text-white font-black uppercase italic tracking-tighter text-xs">
                                            {{ $op->is_active ? 'Deshabilitar Operador' : 'Habilitar Operador' }}
                                        </h3>
                                    </div>
                                    <button type="button" @click="toggling_{{ $op->id }} = false" class="text-white/40 hover:text-white transition">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="bg-white p-6">
                                    <div class="text-center">
                                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full {{ $op->is_active ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} mb-4">
                                            @if($op->is_active)
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            @else
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <p class="text-sm font-bold text-slate-800">
                                            ¿Estás seguro de que deseas {{ $op->is_active ? 'deshabilitar' : 'habilitar' }} al operador <span class="underline">{{ $op->nombre_operador }}</span>?
                                        </p>
                                        <p class="text-xs text-slate-500 mt-2">
                                            {{ $op->is_active 
                                                ? 'Esta acción desactivará al operador del sistema de control y lo retirará de las colas de llamada activas.' 
                                                : 'Esta acción reactivará al operador permitiéndole iniciar sesión y asignarle extensiones en el sistema.' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                                    <button type="button" @click="toggling_{{ $op->id }} = false" class="px-4 py-2 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="text-white px-6 py-2 rounded-lg font-black uppercase text-xs shadow-lg transition-all hover:scale-[1.02] flex items-center gap-2 {{ $op->is_active ? 'bg-red-600 hover:bg-red-700 shadow-red-200' : 'bg-green-600 hover:bg-green-700 shadow-green-200' }}">
                                        @if($op->is_active)
                                            Sí, Deshabilitar
                                        @else
                                            Sí, Habilitar
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal de Edición --}}
                <div x-show="editing_{{ $op->id }}" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="editing_{{ $op->id }}" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="editing_{{ $op->id }} = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        <div x-show="editing_{{ $op->id }}" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                            <form action="{{ route('operadores.update', $op->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="bg-[#1e293b] px-6 py-4 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </div>
                                        <h3 class="text-white font-black uppercase italic tracking-tighter">Editar Operador: {{ $op->nombre_operador }}</h3>
                                    </div>
                                    <button type="button" @click="editing_{{ $op->id }} = false" class="text-white/40 hover:text-white transition">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <div class="bg-white p-6">
                                    <div class="sm:flex sm:items-start">
                                        <div class="text-left w-full">
                                            <div class="space-y-4">
                                                {{-- Multi-select de Extensiones --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Extensiones Asignadas (CTRL+Click para múltiple)</label>
                                                    <select name="extensiones[]" multiple class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-32">
                                                        @foreach($extensionesTotales as $extOpt)
                                                            <option value="{{ $extOpt->numero }}" {{ $op->extensiones->contains('numero', $extOpt->numero) ? 'selected' : '' }}>
                                                                Ext. {{ $extOpt->numero }} {{ $extOpt->grupo_horario ? '— Grp '.$extOpt->grupo_horario : '' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <p class="text-[10px] text-blue-500 mt-1 font-bold">⚠️ Límite de 12 operadores por extensión.</p>
                                                </div>
                                                {{-- Grupo Horario y Bloques --}}
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest mt-4">Grupo Base</label>
                                                        <select name="grupo_horario" class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm font-bold text-slate-700">
                                                            <option value="" {{ empty($op->grupo_horario) ? 'selected' : '' }}>Sin Grupo</option>
                                                            <option value="1" {{ $op->grupo_horario == 1 ? 'selected' : '' }}>Grupo 1 (08:30 a 20:30)</option>
                                                            <option value="2" {{ $op->grupo_horario == 2 ? 'selected' : '' }}>Grupo 2 (20:30 a 08:30)</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest mt-4">Horario de Turno Específico</label>
                                                        <input type="text" name="horario_turno" value="{{ $op->horario_turno }}" placeholder="Ej: 08:00 AM - 05:00 PM" class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm text-slate-700">
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-black text-emerald-600 uppercase mb-1 tracking-widest mt-4">Horario de Comida</label>
                                                        <input type="text" name="horario_comida" value="{{ $op->horario_comida }}" placeholder="Ej: 12:00 PM - 01:00 PM" class="w-full border-emerald-200 rounded-lg focus:ring-emerald-600 text-sm text-slate-700">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-black text-amber-600 uppercase mb-1 tracking-widest mt-4">Horario de Descanso (Break)</label>
                                                        <input type="text" name="horario_descanso" value="{{ $op->horario_descanso }}" placeholder="Ej: 03:00 PM - 03:15 PM" class="w-full border-amber-200 rounded-lg focus:ring-amber-600 text-sm text-slate-700">
                                                    </div>
                                                </div>
                                                {{-- Estado (Activo/Inactivo) --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Estado en Turno</label>
                                                    <select name="is_active" class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="1" {{ $op->is_active ? 'selected' : '' }}>Activo</option>
                                                        <option value="0" {{ !$op->is_active ? 'selected' : '' }}>Inactivo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                                    <button type="button" @click="editing_{{ $op->id }} = false" class="px-4 py-2 text-xs font-bold text-slate-500 uppercase hover:text-slate-800 transition">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs shadow-lg shadow-blue-200 transition-all hover:scale-[1.02] flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
            @endcan
        </tr>
        @empty
        <tr>
            <td colspan="6" class="py-10 text-center text-slate-400 italic text-sm">
                No hay operadores registrados en esta pestaña.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>
