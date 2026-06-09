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
        <tr class="hover:bg-slate-50 transition even:bg-slate-50/50 odd:bg-white" x-data="{ editing_{{ $op->id }}: false }">
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
                <button @click="editing_{{ $op->id }} = true" class="text-blue-500 hover:text-blue-700 p-2 rounded hover:bg-blue-50 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </button>
                
                {{-- Modal de Edición --}}
                <div x-show="editing_{{ $op->id }}" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="editing_{{ $op->id }}" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="editing_{{ $op->id }} = false"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                        <div x-show="editing_{{ $op->id }}" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                            <form action="{{ route('operadores.update', $op->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-black text-slate-800 uppercase tracking-tight">
                                                Editar Operador: {{ $op->nombre_operador }}
                                            </h3>
                                            <div class="mt-4 space-y-4">
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
                                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-lg shadow-blue-200 px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm transition uppercase tracking-widest font-bold">
                                        Guardar
                                    </button>
                                    <button type="button" @click="editing_{{ $op->id }} = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest font-bold">
                                        Cancelar
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
