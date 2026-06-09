<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Cabecera con título y acciones principales --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Gestión de Extensiones</h2>
                <p class="text-xs text-slate-400 mt-1">Extensiones sincronizadas desde FreePBX · Asignación automática a operadores</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('manage-system')
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
                @endcan
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

        <div x-data="{ 
            vistaActiva: new URLSearchParams(window.location.search).get('tab') || '8000',
            isLoading: false,
            loadPage(event, section) {
                let link = event.target.closest('a');
                if (!link) return;
                
                let url = link.href;
                if (!url) return;

                event.preventDefault();
                this.isLoading = true;

                let targetUrl = new URL(url);
                targetUrl.searchParams.set('tab', this.vistaActiva);
                window.history.pushState({}, '', targetUrl.toString());

                fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(res => res.text())
                    .then(html => {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(html, 'text/html');
                        let newContent = doc.querySelector('#partial-' + section).innerHTML;
                        document.querySelector('#partial-' + section).innerHTML = newContent;
                        this.isLoading = false;
                    })
                    .catch(() => {
                        window.location.href = url; // Fallback
                    });
            }
        }"
        x-init="$watch('vistaActiva', val => {
            let url = new URL(window.location.href);
            url.searchParams.set('tab', val);
            window.history.replaceState({}, '', url.toString());
        })"
        class="relative"
        >
            {{-- Loading overlay --}}
            <div x-show="isLoading" style="display: none;" class="absolute inset-0 z-50 flex items-center justify-center bg-white/50 backdrop-blur-sm rounded-2xl">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
            </div>
            {{-- Botonera para alternar vistas --}}
            <div class="flex gap-2 mb-4">
                <button @click="vistaActiva = '8000'" :class="vistaActiva === '8000' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                    Operadores (8001-8006)
                </button>
                <button @click="vistaActiva = 'desp'" :class="vistaActiva === 'desp' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                    Despachadores (8007-8008)
                </button>
                <button @click="vistaActiva = '9000'" :class="vistaActiva === '9000' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                    Ver Extensiones 9000
                </button>
                <button @click="vistaActiva = 'inactivas'" :class="vistaActiva === 'inactivas' ? 'bg-red-600 text-white shadow-lg shadow-red-200' : 'bg-white text-slate-500 border border-slate-200 hover:bg-slate-50'" class="px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all">
                    Inactivas
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

            <div id="partial-8000" @click="loadPage($event, '8000')">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                            <tr class="border-b border-slate-800">
                                <th class="px-6 py-4 font-bold">Extensión</th>
                                <th class="px-6 py-4 font-bold">Nombre FreePBX</th>
                                <th class="px-6 py-4 text-center font-bold">Local</th>
                                <th class="px-6 py-4 text-center font-bold">AMI</th>
                                <th class="px-6 py-4 font-bold">Grupo Asignado</th>
                                @can('manage-system')
                                <th class="px-6 py-4 text-right font-bold">Acciones</th>
                                @endcan
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
        </div>

            {{-- Sección: Despachadores (8007-8008) --}}
            <div x-show="vistaActiva === 'desp'" style="display: none;" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden mb-6">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-white uppercase tracking-tighter italic text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Extensiones de Despachadores (8007-8008)
                    </h3>
                </div>

            <div id="partial-desp" @click="loadPage($event, 'desp')">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                            <tr class="border-b border-slate-800">
                                <th class="px-6 py-4 font-bold">Extensión</th>
                                <th class="px-6 py-4 font-bold">Nombre FreePBX</th>
                                <th class="px-6 py-4 text-center font-bold">Local</th>
                                <th class="px-6 py-4 text-center font-bold">AMI</th>
                                <th class="px-6 py-4 font-bold">Grupo Asignado</th>
                                @can('manage-system')
                                <th class="px-6 py-4 text-right font-bold">Acciones</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($extensionsDespachadores as $ext)
                                @include('extensions._row', ['ext' => $ext, 'estadosAmi' => $estadosAmi])
                            @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400 italic text-sm">
                                    No hay extensiones de despachadores registradas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($extensionsDespachadores->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                        {{ $extensionsDespachadores->appends(request()->except('page_desp'))->links() }}
                    </div>
                @endif
            </div>
        </div>

            {{-- Sección: Internos (9xxx) --}}
            <div x-show="vistaActiva === '9000'" style="display: none;" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-white uppercase tracking-tighter italic text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        Teléfonos Internos / Estaciones (Serie 9000)
                    </h3>
                </div>

            <div id="partial-9000" @click="loadPage($event, '9000')">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                            <tr class="border-b border-slate-800">
                                <th class="px-6 py-4 font-bold">Extensión</th>
                                <th class="px-6 py-4 font-bold">Nombre FreePBX</th>
                                <th class="px-6 py-4 text-center font-bold">Local</th>
                                <th class="px-6 py-4 text-center font-bold">AMI</th>
                                <th class="px-6 py-4 font-bold">Grupo Asignado</th>
                                @can('manage-system')
                                <th class="px-6 py-4 text-right font-bold">Acciones</th>
                                @endcan
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

            {{-- Sección: Inactivas --}}
            <div x-show="vistaActiva === 'inactivas'" style="display: none;" x-transition class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex items-center justify-between">
                    <h3 class="font-black text-white uppercase tracking-tighter italic text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Extensiones Inactivas (Deshabilitadas)
                    </h3>
                </div>

            <div id="partial-inactivas" @click="loadPage($event, 'inactivas')">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                            <tr class="border-b border-slate-800">
                                <th class="px-6 py-4 font-bold">Extensión</th>
                                <th class="px-6 py-4 font-bold">Nombre FreePBX / Motivo</th>
                                <th class="px-6 py-4 text-center font-bold">Local</th>
                                <th class="px-6 py-4 text-center font-bold">AMI</th>
                                <th class="px-6 py-4 font-bold">Grupo Asignado</th>
                                @can('manage-system')
                                <th class="px-6 py-4 text-right font-bold">Acciones</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($extensionsInactivas as $ext)
                                @include('extensions._row', ['ext' => $ext, 'estadosAmi' => $estadosAmi])
                            @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-400 italic text-sm">
                                    No hay extensiones inactivas.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($extensionsInactivas->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                        {{ $extensionsInactivas->appends(request()->except('page_inactivas'))->links() }}
                    </div>
                @endif
            </div>
            </div>
        </div>
    </div>

    {{-- Modal para Habilitar Seguro --}}
    <div x-data="{ open: false, extId: null, extNumero: null }"
         @open-enable-modal.window="open = true; extId = $event.detail.id; extNumero = $event.detail.numero"
         x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open = false"></div>
            
            <div x-show="open" x-transition class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6 overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-emerald-500"></div>
                <h3 class="text-lg font-black text-slate-800 mb-2">Habilitar Extensión <span x-text="extNumero" class="text-emerald-600"></span></h3>
                <p class="text-sm text-slate-500 mb-6">Para habilitar esta extensión, debes confirmar tu identidad introduciendo tu contraseña de administrador.</p>
                
                <form :action="`{{ url('extensions') }}/${extId}/enable-secure`" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Contraseña de Administrador</label>
                        <input type="password" name="password" id="password" required class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-slate-800">
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">Cancelar</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02] flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Confirmar Habilitación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>