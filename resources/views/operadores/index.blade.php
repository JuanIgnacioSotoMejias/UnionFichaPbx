<x-app-layout>
    <div class="p-6 space-y-6" x-data="{ activeTab: 'disponibles', editing: null }">

        {{-- Cabecera --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestión de Personal</h2>
                <p class="text-xs text-slate-400 mt-1">Administra los operadores y su asignación a extensiones (Max 12 por Extensión).</p>
            </div>
            @can('manage-system')
            <div>
                <form action="{{ route('operadores.sincronizarFicha') }}" method="POST" onsubmit="this.querySelector('button').disabled=true; this.querySelector('.spinner').classList.remove('hidden');">
                    @csrf
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-4 py-2 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89M9 11l3 3L22 4"/></svg>
                        Sincronizar desde Ficha
                        <svg class="spinner hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>
            </div>
            @endcan
        </div>

        {{-- Mensajes Flash y Errores --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Pestañas de Navegación --}}
        <div class="flex flex-wrap gap-2 border-b-2 border-slate-200 pb-2">
            <button @click="activeTab = 'disponibles'" 
                    :class="activeTab === 'disponibles' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                    class="px-4 py-2 rounded-t-xl font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2">
                Personal Disponible
                <span class="px-2 py-0.5 rounded-full text-[9px] bg-emerald-500 text-white">{{ $operadoresDisponibles->count() }}</span>
            </button>
            
            @foreach($extensionesConOperadores as $ext)
            <button @click="activeTab = 'ext_{{ $ext->numero }}'" 
                    :class="activeTab === 'ext_{{ $ext->numero }}' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
                    class="px-4 py-2 rounded-t-xl font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2">
                Extensión {{ $ext->numero }}
                <span class="px-2 py-0.5 rounded-full text-[9px] bg-blue-600 text-white">{{ $ext->operadores->count() }}/12</span>
            </button>
            @endforeach

            @if($operadoresDeshabilitados->count() > 0)
            <button @click="activeTab = 'deshabilitados'" 
                    :class="activeTab === 'deshabilitados' ? 'bg-red-600 text-white' : 'bg-white text-red-600 hover:bg-red-50 border border-red-200'"
                    class="px-4 py-2 rounded-t-xl font-bold uppercase tracking-widest text-[10px] transition-all flex items-center gap-2">
                Personal Deshabilitado
                <span class="px-2 py-0.5 rounded-full text-[9px] bg-red-800 text-white">{{ $operadoresDeshabilitados->count() }}</span>
            </button>
            @endif
        </div>

        {{-- Contenedor de las Tablas --}}
        <div class="bg-white rounded-b-2xl rounded-tr-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden mb-6 -mt-6 z-10 relative">
            
            {{-- Pestaña: Disponibles --}}
            <div x-show="activeTab === 'disponibles'" class="overflow-x-auto">
                @include('operadores._tabla', ['operadores' => $operadoresDisponibles, 'contexto' => 'disponibles'])
            </div>

            {{-- Pestañas: Extensiones --}}
            @foreach($extensionesConOperadores as $ext)
            <div x-show="activeTab === 'ext_{{ $ext->numero }}'" style="display: none;" class="overflow-x-auto">
                <div class="bg-slate-50 border-b border-slate-200 p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-800 uppercase tracking-tight">Operadores en Extensión {{ $ext->numero }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Límite de capacidad: {{ $ext->operadores->count() }} de 12 asignados.</p>
                    </div>
                </div>
                @include('operadores._tabla', ['operadores' => $ext->operadores, 'contexto' => 'ext_'.$ext->numero])
            </div>
            @endforeach

            {{-- Pestaña: Deshabilitados --}}
            @if($operadoresDeshabilitados->count() > 0)
            <div x-show="activeTab === 'deshabilitados'" style="display: none;" class="overflow-x-auto">
                <div class="bg-red-50 border-b border-red-200 p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-red-800 uppercase tracking-tight">Personal Deshabilitado</h3>
                        <p class="text-xs text-red-500 font-medium mt-1">Operadores y despachadores que han sido desactivados del sistema.</p>
                    </div>
                </div>
                @include('operadores._tabla', ['operadores' => $operadoresDeshabilitados, 'contexto' => 'deshabilitados'])
            </div>
            @endif

        </div>

    </div>
</x-app-layout>
