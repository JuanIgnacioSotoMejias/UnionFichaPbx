<tr class="hover:bg-slate-50 transition group">
    {{-- Número de extensión --}}
    <td class="px-6 py-4">
        <span class="bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg font-mono text-sm font-black border border-slate-200">
            {{ $ext->numero }}
        </span>
    </td>

    {{-- Nombre FreePBX --}}
    <td class="px-6 py-4">
        <div>
            <p class="text-sm font-bold text-slate-800">{{ $ext->nombre_freepbx ?? $ext->descripcion ?? '—' }}</p>
            @if($ext->descripcion && $ext->descripcion !== $ext->nombre_freepbx)
                <p class="text-[10px] text-slate-400">{{ $ext->descripcion }}</p>
            @endif
            @if(!$ext->is_active && $ext->motivo_inactividad)
                <p class="text-[10px] text-red-500 mt-1"><strong>Motivo:</strong> {{ $ext->motivo_inactividad }}</p>
            @endif
        </div>
    </td>

    {{-- Estado Local --}}
    <td class="px-6 py-4 text-center">
        @if(!$ext->is_active)
            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-red-200">Inactiva</span>
        @elseif($ext->estado === 'libre')
            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-200">Libre</span>
        @elseif($ext->estado === 'en_uso')
            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-blue-200">En Uso</span>
        @else
            <span class="bg-slate-100 text-slate-500 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-slate-200">Inactiva</span>
        @endif
    </td>

    {{-- Estado AMI (real-time) --}}
    <td class="px-6 py-4 text-center">
        @php
            $estadoAmi = strtoupper($estadosAmi[$ext->numero] ?? 'OFFLINE');
            if ($estadoAmi === 'ONLINE') {
                $amiClass = 'bg-green-100 text-green-800 border-green-200';
                $amiLabel = 'ONLINE';
            } else {
                $amiClass = 'bg-red-100 text-red-800 border-red-200';
                $amiLabel = 'OFFLINE';
            }
        @endphp
        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $amiClass }}">
            {{ $amiLabel }}
        </span>
    </td>

    {{-- Grupo Asignado --}}
    <td class="px-6 py-4">
        @if($ext->grupo_horario)
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-blue-100 border border-blue-200 flex items-center justify-center text-[10px] font-black text-blue-600 uppercase">
                    G{{ $ext->grupo_horario }}
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Grupo {{ $ext->grupo_horario }}</p>
                </div>
            </div>
        @else
            <span class="text-[10px] text-slate-300 italic">Sin grupo asignado</span>
        @endif
    </td>

    {{-- Acciones --}}
    @can('manage-system')
    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end gap-1 opacity-60 group-hover:opacity-100 transition">
            {{-- Liberar extensión --}}
            @if($ext->estado === 'en_uso')
            <form action="{{ route('extensions.liberar', $ext) }}" method="POST" onsubmit="return confirm('¿Liberar la extensión {{ $ext->numero }} forzosamente?');">
                @csrf
                <button type="submit" title="Liberar" class="p-2 rounded-lg hover:bg-amber-50 text-amber-500 hover:text-amber-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </button>
            </form>
            @endif

            {{-- Editar --}}
            <a href="{{ route('extensions.edit', $ext) }}" title="Editar" class="p-2 rounded-lg hover:bg-blue-50 text-blue-500 hover:text-blue-700 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </a>

            {{-- Eliminar/Deshabilitar --}}
            @if(!$ext->is_active && in_array($ext->numero, ['8009', '8010']))
                <button type="button" @click="$dispatch('open-enable-modal', { id: '{{ $ext->id }}', numero: '{{ $ext->numero }}' })" title="Habilitar Seguro" class="p-2 rounded-lg transition hover:bg-emerald-50 text-emerald-500 hover:text-emerald-700">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </button>
            @else
                <form action="{{ route('extensions.destroy', $ext) }}" method="POST" onsubmit="if({{ $ext->is_active ? 'true' : 'false' }}) { let m = prompt('Motivo de inactividad para la extensión {{ $ext->numero }}:'); if(m === null) return false; this.motivo_inactividad.value = m; } return confirm('¿Seguro que deseas {{ $ext->is_active ? 'deshabilitar' : 'habilitar' }} la extensión {{ $ext->numero }}?');">
                    @csrf @method('DELETE')
                    <input type="hidden" name="motivo_inactividad" value="">
                    <button type="submit" title="{{ $ext->is_active ? 'Deshabilitar' : 'Habilitar' }}" class="p-2 rounded-lg transition {{ $ext->is_active ? 'hover:bg-amber-50 text-amber-500 hover:text-amber-700' : 'hover:bg-emerald-50 text-emerald-500 hover:text-emerald-700' }}">
                        @if($ext->is_active)
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728"/></svg>
                        @else
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </button>
                </form>
            @endif
        </div>
    </td>
    @endcan
</tr>
