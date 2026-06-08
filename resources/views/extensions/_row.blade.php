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
        </div>
    </td>

    {{-- Estado Local --}}
    <td class="px-6 py-4 text-center">
        @if($ext->estado === 'libre')
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

            {{-- Eliminar --}}
            <form action="{{ route('extensions.destroy', $ext) }}" method="POST" onsubmit="return confirm('¿Eliminar la extensión {{ $ext->numero }}? Esta acción no se puede deshacer.');">
                @csrf @method('DELETE')
                <button type="submit" title="Eliminar" class="p-2 rounded-lg hover:bg-red-50 text-red-400 hover:text-red-700 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </form>
        </div>
    </td>
</tr>
