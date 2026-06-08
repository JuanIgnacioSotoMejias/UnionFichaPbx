<x-app-layout>
    <div class="p-6 max-w-2xl mx-auto space-y-6">
        
        <div class="flex items-center gap-3">
            <a href="{{ route('extensions.index') }}" class="p-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Añadir Extensión</h2>
                <p class="text-xs text-slate-400 mt-1">Registro manual de dispositivo SIP/PJSIP</p>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                <h3 class="font-black text-slate-800 uppercase tracking-tighter italic text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Datos de la Extensión
                </h3>
            </div>

            <form action="{{ route('extensions.store') }}" method="POST" class="p-6 space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Número de Extensión <span class="text-red-500">*</span></label>
                    <input type="text" name="numero" value="{{ old('numero') }}" placeholder="Ej. 1001" required
                        class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-800 font-mono focus:ring-blue-500 focus:border-blue-500 @error('numero') border-red-500 @enderror">
                    @error('numero')
                        <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                    <input type="text" name="descripcion" value="{{ old('descripcion') }}" placeholder="Ej. Teléfono Recepción"
                        class="block w-full rounded-lg border-slate-300 bg-slate-50 text-slate-800 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-[10px] text-slate-400 mt-1">Se usará como identificador local si no coincide con FreePBX.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                    <a href="{{ route('extensions.index') }}" class="px-4 py-2.5 text-sm font-bold text-slate-500 hover:text-slate-700 transition">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-bold uppercase tracking-wider shadow-lg shadow-blue-200 transition-all hover:scale-[1.02]">
                        Guardar Extensión
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>