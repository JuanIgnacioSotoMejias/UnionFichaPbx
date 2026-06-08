<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Cabecera con título y acciones principales --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestión de Extensiones</h2>
                <p class="text-xs text-slate-400 mt-1">Extensiones sincronizadas desde FreePBX · Asignación automática a operadores</p>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- Botón Sincronizar desde FreePBX --}}
                <form action="{{ route('extensions.sincronizar') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Sincronizar FreePBX
                    </button>
                </form>

                {{-- Botón Añadir Manual --}}
                <a href="{{ route('extensions.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-200 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Añadir Manual
                </a>
            </div>
        </div>

        {{-- Mensajes Flash --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                {{ session('warning') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        @if(isset($amiError))
            <div class="bg-amber-50 border border-amber-200 text-amber-700 p-4 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0 text-amber-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span><strong>Advertencia:</strong> {{ $amiError }} (Los estados de las extensiones se muestran OFFLINE de forma preventiva).</span>
            </div>
        @endif



        {{-- Resumen de estadísticas --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-black text-slate-800">{{ $totalExtensions->count() }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Total Central</p>
            </div>
            <div class="bg-white rounded-xl border border-emerald-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-black text-emerald-600">{{ $totalExtensions->where('estado', 'libre')->count() }}</p>
                <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest mt-1">Libres Globales</p>
            </div>
            <div class="bg-white rounded-xl border border-blue-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-black text-blue-600">{{ $totalExtensions->where('estado', 'en_uso')->count() }}</p>
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mt-1">En Uso Global</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-4 text-center shadow-sm">
                <p class="text-2xl font-black text-slate-400">{{ $totalExtensions->where('estado', 'inactiva')->count() }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Inactivas Globales</p>
            </div>
        </div>

        <div x-data="{ vistaActiva: '8000' }">
            {{-- Botonera para alternar vistas --}}
            <div class="flex gap-2 mb-4">
                <button @click="vistaActiva = '8000'" :class="vistaActiva === '8000' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                    Ver Extensiones 8000
                </button>
                <button @click="vistaActiva = '9000'" :class="vistaActiva === '9000' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                    Ver Extensiones 9000
                </button>
            </div>

            {{-- Sección: Operadores (8xxx) --}}
            <div x-show="vistaActiva === '8000'" style="display: none;" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden mb-6">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-white uppercase tracking-tighter italic text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Extensiones de Operadores (Serie 8000)
                    </h3>
                </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                        <tr class="border-b border-slate-800">
                            <th class="px-6 py-4 font-bold">Extensión</th>
                            <th class="px-6 py-4 font-bold">Nombre FreePBX</th>
                            <th class="px-6 py-4 text-center font-bold">Local</th>
                            <th class="px-6 py-4 text-center font-bold">AMI</th>
                            <th class="px-6 py-4 font-bold">Grupo Asignado</th>
                            <th class="px-6 py-4 text-right font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($extensionsOperadores as $ext)
                            @include('extensions._row', ['ext' => $ext, 'estadosAmi' => $estadosAmi])
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 italic text-sm">
                                No hay extensiones serie 8000 registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($extensionsOperadores->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $extensionsOperadores->appends(request()->except('page_op'))->links() }}
                </div>
            @endif
        </div>

            {{-- Sección: Internos (9xxx) --}}
            <div x-show="vistaActiva === '9000'" style="display: none;" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-white uppercase tracking-tighter italic text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Teléfonos Internos / Estaciones (Serie 9000)
                    </h3>
                </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                        <tr class="border-b border-slate-800">
                            <th class="px-6 py-4 font-bold">Extensión</th>
                            <th class="px-6 py-4 font-bold">Nombre FreePBX</th>
                            <th class="px-6 py-4 text-center font-bold">Local</th>
                            <th class="px-6 py-4 text-center font-bold">AMI</th>
                            <th class="px-6 py-4 font-bold">Grupo Asignado</th>
                            <th class="px-6 py-4 text-right font-bold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($extensionsInternos as $ext)
                            @include('extensions._row', ['ext' => $ext, 'estadosAmi' => $estadosAmi])
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 italic text-sm">
                                No hay extensiones serie 9000 registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($extensionsInternos->hasPages())
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $extensionsInternos->appends(request()->except('page_int'))->links() }}
                </div>
            @endif
            </div>
        </div>
    </div>
</x-app-layout>