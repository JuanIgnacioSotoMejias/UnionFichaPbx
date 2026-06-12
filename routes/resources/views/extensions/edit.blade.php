<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto space-y-6">
        
        <div class="flex items-center gap-3">
            <a href="{{ route('extensions.index') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Editar Extensión</h2>
                <p class="text-xs text-slate-400 mt-1">Actualizando configuración de la extensión <span class="font-mono font-bold">{{ $extension->numero }}</span></p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h3 class="font-black text-slate-800 uppercase tracking-tighter italic text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Ajustes de Dispositivo
                </h3>
            </div>

            <form action="{{ route('extensions.update', $extension) }}" method="POST" class="p-6 space-y-5">
                @csrf @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Número de Extensión <span class="text-red-500">*</span></label>
                    <input type="text" name="numero" value="{{ old('numero', $extension->numero) }}" required
                        class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-800 font-mono font-bold focus:ring-amber-500 focus:border-amber-500 @error('numero') border-red-500 @enderror">
                    @error('numero')
                        <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                    <input type="text" name="descripcion" value="{{ old('descripcion', $extension->descripcion) }}"
                        class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-800 focus:ring-amber-500 focus:border-amber-500">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Estado Operativo</label>
                    <select name="estado" class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-800 focus:ring-amber-500 focus:border-amber-500 font-bold">
                        <option value="libre" {{ $extension->estado === 'libre' ? 'selected' : '' }}>Libre (Disponible para Auto-Asignación)</option>
                        <option value="en_uso" {{ $extension->estado === 'en_uso' ? 'selected' : '' }}>En Uso</option>
                        <option value="inactiva" {{ $extension->estado === 'inactiva' ? 'selected' : '' }}>Inactiva (Bloqueada)</option>
                    </select>
                    @if($extension->estado === 'en_uso')
                        <p class="text-[10px] text-amber-600 font-bold mt-2 bg-amber-50 p-2 rounded border border-amber-100">
                            ⚠️ Cambiar el estado a "Libre" desvinculará automáticamente al operador actual.
                        </p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Grupo Horario</label>
                    <select name="grupo_horario" class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-800 focus:ring-amber-500 focus:border-amber-500 font-bold">
                        <option value="" {{ empty($extension->grupo_horario) ? 'selected' : '' }}>Sin grupo asignado</option>
                        <option value="1" {{ $extension->grupo_horario == 1 ? 'selected' : '' }}>Grupo 1</option>
                        <option value="2" {{ $extension->grupo_horario == 2 ? 'selected' : '' }}>Grupo 2</option>
                    </select>
                    <p class="text-[10px] text-blue-500 font-bold mt-1">ℹ️ Los operadores asignados a esta extensión heredarán automáticamente este grupo horario.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('extensions.index') }}" class="px-4 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider shadow-lg shadow-amber-200 transition-all hover:scale-[1.02]">
                        Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>