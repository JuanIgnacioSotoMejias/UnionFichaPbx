<x-app-layout>
    <div class="p-6 space-y-6" x-data="{
        alerts: [],
        baseErrors: {{ $erroresHoy }},
        get errorCount() { return this.baseErrors + this.alerts.length; },
        showModal: false,
        addAlert(alerta) {
            this.alerts.unshift({
                ...alerta,
                time: new Date().toLocaleTimeString()
            });
        },
        clearAlerts() {
            this.alerts = [];
            this.baseErrors = 0; // Opcional: limpiar también los errores base de la sesión si se desea
        }
    }" @nueva-alerta-global.window="addAlert($event.detail)">

        {{-- ═══════════════════════════════════════════════════════════════
             BANNER DE PRODUCTIVIDAD (OPERADOR ACTUAL)
             ═══════════════════════════════════════════════════════════════ --}}
        <div id="productivity-banner">
            @if($extension)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-800 uppercase tracking-wide">Tu Productividad</h2>
                        <p class="text-xs text-slate-500 font-mono">Extensión: {{ $extension }}</p>
                    </div>
                </div>
                
                @if(config('app.metrics_enabled'))
                <div class="flex items-center gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">AHT (Promedio)</p>
                        <span class="text-lg font-black text-slate-800">{{ $aht }}</span>
                    </div>
                    
                    <div class="w-px h-10 bg-slate-200"></div>

                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 flex justify-between">
                            <span>Ocupación</span>
                            <span class="{{ $colorOcupacion }}">{{ $ocupacion }}%</span>
                        </p>
                        <div class="w-32 bg-slate-100 h-2 rounded-full overflow-hidden mt-1">
                            <div class="{{ $bgOcupacion }} h-full transition-all duration-700" style="width: {{ $ocupacion }}%"></div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             FILA 1 — TARJETAS DE KPI (4 COLUMNAS)
             ═══════════════════════════════════════════════════════════════ --}}
        <div id="kpi-cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- Tarjeta 1: Estado AMI / FreePBX --}}
            <div id="pbx-status-card" title="{{ $amiMensaje }}"
                 class="group relative overflow-hidden rounded-2xl p-4 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-default {{ $amiConectado ? 'bg-emerald-700' : 'bg-rose-700' }}">
                <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>

                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">Enlace API FreePBX</p>
                        <p id="pbx-status-text" class="text-2xl font-black text-white leading-none">
                            {{ $amiConectado ? 'Conectado' : 'Desconectado' }}
                        </p>
                        <p class="text-[10px] text-white/60 mt-1 font-mono font-medium">Puerto 5038 · AMI</p>
                    </div>
                    <div id="pbx-status-icon-bg" class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                        </svg>
                    </div>
                </div>

                <div class="relative mt-3 flex items-center gap-2">
                    <span id="pbx-status-dot" class="w-2.5 h-2.5 rounded-full {{ $amiConectado ? 'bg-white animate-pulse' : 'bg-white/40' }}"></span>
                    <span id="pbx-status-subtext" class="text-xs font-bold text-white/80 uppercase tracking-wider">
                        {{ $amiConectado ? 'En línea' : 'Fuera de línea' }}
                    </span>
                </div>
            </div>

            {{-- Tarjeta 2: Operadores Activos --}}
            <div class="group relative overflow-hidden rounded-2xl p-4 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-default bg-slate-800">
                <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>

                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">Op. Locales Activos</p>
                        <p class="text-2xl font-black text-white leading-none">
                            {{ $operadoresActivos }}
                            <span class="text-sm font-bold text-white/50">/ {{ $totalOperadores }}</span>
                        </p>
                        <p class="text-[10px] text-white/60 mt-1 font-mono font-medium">Operadores en turno</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="relative mt-3">
                    <div class="w-full bg-white/20 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-white h-1.5 rounded-full transition-all duration-700"
                             style="width: {{ $totalOperadores > 0 ? round(($operadoresActivos / $totalOperadores) * 100) : 0 }}%">
                        </div>
                    </div>
                    <p class="text-[10px] font-medium text-white/60 mt-1">
                        {{ $totalOperadores > 0 ? round(($operadoresActivos / $totalOperadores) * 100) : 0 }}% del equipo activo
                    </p>
                </div>
            </div>

            {{-- Tarjeta 3: Eventos de Hoy --}}
            <div class="group relative overflow-hidden rounded-2xl p-4 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-default bg-amber-600">
                <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>

                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">Eventos de Hoy</p>
                        <p class="text-2xl font-black text-white leading-none">{{ $totalEventosHoy }}</p>
                        <p class="text-[10px] text-white/60 mt-1 font-mono font-medium">Login / Logout acumulados</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="relative mt-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                    <span class="text-xs font-bold text-white/80 uppercase">Actualización en tiempo real</span>
                </div>
            </div>

            {{-- Tarjeta 4: Errores Receptora --}}
            <div class="group relative overflow-hidden rounded-2xl p-4 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-default bg-rose-700">
                <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>

                <div class="relative flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">Errores Receptora</p>
                        <p class="text-2xl font-black text-white leading-none" x-text="errorCount">{{ $erroresHoy }}</p>
                        <p class="text-[10px] text-white/60 mt-1 font-mono font-medium">Errores 4xx / 5xx hoy</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>

                <div class="relative mt-3 flex items-center gap-2" style="min-height: 24px;">
                    <template x-if="errorCount > 0">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                                <span class="text-[10px] font-bold text-white/90 uppercase">¡Requiere revisión!</span>
                            </div>
                            <button @click="showModal = true" class="text-[9px] bg-white/20 hover:bg-white/30 text-white px-2 py-1 rounded font-bold uppercase tracking-widest transition border border-white/20">Ver información</button>
                        </div>
                    </template>
                    <template x-if="errorCount === 0">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-white/40"></span>
                            <span class="text-xs font-bold text-white/80 uppercase">Sin errores detectados</span>
                        </div>
                    </template>
                </div>
            </div>
        </div>



        {{-- ═══════════════════════════════════════════════════════════════
             SECCIÓN DE CONTENIDO PRINCIPAL: TABLA + FEED LATERAL
             ═══════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- Bloque Izquierdo: Operadores de Turno (7 Columnas) --}}
            <div id="tabla-operadores-container" class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 {{ $amiConectado ? 'bg-green-500 animate-pulse' : 'bg-red-500' }} rounded-full"></span>
                        <h3 class="font-black text-white uppercase tracking-tighter italic text-sm">Operadores de Turno</h3>
                        <span class="hidden md:inline-block text-[9px] {{ $amiConectado ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-2 py-0.5 rounded-full font-bold uppercase tracking-widest ml-2">
                            Real-Time
                        </span>
                    </div>
                    
                    {{-- Filtro de Flujo de Trabajo --}}
                    <form action="{{ route('dashboard') }}" method="GET" class="flex items-center gap-2">
                        <label for="periodo" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Flujo de Trabajo:</label>
                        <select name="periodo" id="periodo" onchange="this.form.submit()" class="text-xs font-bold text-slate-700 border-slate-300 rounded-lg py-1.5 pl-3 pr-8 focus:ring-blue-500 focus:border-blue-500">
                            <option value="dia" {{ $periodoFiltro === 'dia' ? 'selected' : '' }}>Hoy</option>
                            <option value="semana" {{ $periodoFiltro === 'semana' ? 'selected' : '' }}>Esta Semana</option>
                            <option value="mes" {{ $periodoFiltro === 'mes' ? 'selected' : '' }}>Este Mes</option>
                        </select>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left border-collapse">
                        <thead class="bg-slate-900">
                            <tr class="text-white text-sm uppercase font-semibold tracking-wider">
                                <th class="px-4 py-3 font-bold">Datos del Operador</th>
                                <th class="px-4 py-3 font-bold text-center">Extensión</th>
                                @if(config('app.metrics_enabled'))
                                <th class="px-4 py-3 font-bold text-center">AHT</th>
                                <th class="px-4 py-3 font-bold text-center">Ocupación</th>
                                @endif
                                <th class="px-4 py-3 font-bold text-center">Estado Turno</th>
                                <th class="px-4 py-3 font-bold text-right">Estado FreePBX</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($operadores as $op)
                            <tr class="hover:bg-slate-100 transition even:bg-gray-50 odd:bg-white">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                                            {{ substr($op->nombre_operador, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-800">{{ $op->nombre_operador }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">{{ $op->ficha_username ?? 'Sin usuario Ficha' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded font-mono text-sm font-bold border border-slate-200">
                                        {{ $op->extension }}
                                    </span>
                                </td>
                                @php
                                    $stats = collect($reporteProductividad)->firstWhere('id', $op->id);
                                    $ahtSeconds = $stats['aht'] ?? 0;
                                    $ocupacion = $stats['ocupacion'] ?? 0;
                                    $telemetry = app(\App\Services\TelemetryService::class);
                                    $ahtFormatted = $telemetry->formatAHT($ahtSeconds);
                                    
                                    if ($ocupacion < 70) {
                                        $colorOcupacion = 'bg-slate-300';
                                        $textOcupacion = 'text-slate-500';
                                    } elseif ($ocupacion >= 70 && $ocupacion <= 90) {
                                        $colorOcupacion = 'bg-emerald-500';
                                        $textOcupacion = 'text-emerald-600';
                                    } else {
                                        $colorOcupacion = 'bg-red-500';
                                        $textOcupacion = 'text-red-600';
                                    }
                                @endphp
                                @if(config('app.metrics_enabled'))
                                <td class="px-4 py-3 text-center">
                                    <span class="text-sm font-bold {{ $ahtSeconds > 300 ? 'text-red-500' : 'text-slate-600' }}">
                                        {{ $ahtFormatted }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center gap-2 justify-center">
                                        <div class="w-12 bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                            <div class="{{ $colorOcupacion }} h-full transition-all duration-500" style="width: {{ $ocupacion }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold {{ $textOcupacion }}">{{ $ocupacion }}%</span>
                                    </div>
                                </td>
                                @endif
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $estadoTurno = $scheduleService->getCurrentState($op);
                                        $labelTurno = $scheduleService->getStateLabel($estadoTurno);
                                    @endphp
                                    <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest {{ $labelTurno['class'] }}">
                                        {{ $labelTurno['text'] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @php
                                        $estado = strtolower($op->estatus_pbx ?? 'offline');
                                        
                                        if (in_array($estado, ['online', 'not_inuse', 'idle'])) {
                                            $claseEstado = 'bg-green-100 text-green-700 border-green-200';
                                            $textoMostrar = 'Disponible';
                                        } elseif (in_array($estado, ['inuse', 'busy'])) {
                                            $claseEstado = 'bg-amber-100 text-amber-700 border-amber-200';
                                            $textoMostrar = 'En Llamada';
                                        } elseif ($estado === 'ringing') {
                                            $claseEstado = 'bg-blue-100 text-blue-700 border-blue-200 animate-pulse';
                                            $textoMostrar = 'Repicando';
                                        } else {
                                            $claseEstado = 'bg-red-100 text-red-700 border-red-200';
                                            $textoMostrar = 'Desconectado';
                                        }
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border {{ $claseEstado }}">
                                        {{ $textoMostrar }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ config('app.metrics_enabled') ? 6 : 4 }}" class="py-10 text-center text-slate-400 italic text-xs">
                                    No hay operadores registrados en el middleware local.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($operadores instanceof \Illuminate\Pagination\LengthAwarePaginator && $operadores->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                        <div class="flex flex-row flex-wrap items-center justify-between gap-2">
                            {{ $operadores->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>

            {{-- Bloque Derecho: Últimos Accesos (5 Columnas) --}}
            <div id="live-feed-container" class="lg:col-span-5 bg-white rounded-2xl shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] border border-slate-200 overflow-hidden self-stretch flex flex-col">
                <div class="bg-slate-900 px-6 py-4 border-b border-slate-800 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        <h3 class="font-black text-white uppercase text-xs tracking-widest">Últimos Accesos</h3>
                    </div>
                    <span class="text-[9px] font-mono text-blue-300 uppercase tracking-widest">Live Feed</span>
                </div>

                <div class="p-5 overflow-hidden space-y-3 flex-1">
                    @forelse($ultimosEventos as $acceso)
                    <div class="flex items-start gap-3 p-3 rounded-xl {{ $acceso->evento === 'LOGOUT' ? 'bg-amber-50 border border-amber-100' : 'bg-emerald-50 border border-emerald-100' }}">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $acceso->evento === 'LOGOUT' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-700' }}">
                            @if($acceso->evento === 'LOGIN')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-sm font-semibold text-slate-800 truncate">
                                    {{ $acceso->operador->nombre_operador ?? 'Sistema' }}
                                </p>
                                <span class="flex-shrink-0 text-[10px] font-black uppercase px-2 py-0.5 rounded-full {{ $acceso->evento === 'LOGIN' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $acceso->evento }}
                                </span>
                            </div>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">
                                {{ is_string($acceso->created_at) ? date('d/m H:i:s', strtotime($acceso->created_at)) : $acceso->created_at->format('d/m H:i:s') }}
                                · IP: {{ $acceso->origen_ip ?? '—' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="py-10 flex flex-col items-center justify-center text-center gap-2">
                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-slate-400 italic text-xs">Sin eventos recientes</p>
                    </div>
                    @endforelse
                </div>
                @if($ultimosEventos instanceof \Illuminate\Pagination\LengthAwarePaginator && $ultimosEventos->hasPages())
                    <div class="px-4 py-3 border-t border-slate-200 bg-slate-50 mt-auto">
                        <div class="flex flex-row flex-wrap items-center justify-between gap-2">
                            {{ $ultimosEventos->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        {{-- ═══════════════════════════════════════════════════════════════
             MODAL DE ALERTAS CRÍTICAS (ALPINEJS)
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" @click="showModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="showModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5 border-b border-slate-100 pb-4">
                            <h3 class="text-lg leading-6 font-black text-rose-600 uppercase tracking-tight flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Registro de Alertas Críticas
                            </h3>
                            <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        
                        <div class="max-h-[60vh] overflow-y-auto pr-2 space-y-3">
                            <template x-for="(alerta, index) in alerts" :key="index">
                                <div class="p-4 rounded-xl border-l-4 shadow-sm bg-slate-50" :class="{
                                    'border-rose-500': alerta.nivel === 'CRITICAL',
                                    'border-amber-500': alerta.nivel === 'WARNING',
                                    'border-blue-500': alerta.nivel !== 'CRITICAL' && alerta.nivel !== 'WARNING'
                                }">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full" 
                                              :class="{
                                                  'bg-rose-100 text-rose-700': alerta.nivel === 'CRITICAL',
                                                  'bg-amber-100 text-amber-700': alerta.nivel === 'WARNING',
                                                  'bg-blue-100 text-blue-700': alerta.nivel !== 'CRITICAL' && alerta.nivel !== 'WARNING'
                                              }" x-text="alerta.tipo_alerta"></span>
                                        <span class="text-[10px] font-mono text-slate-400" x-text="alerta.time"></span>
                                    </div>
                                    <p class="text-xs font-bold text-slate-800 leading-tight" x-text="alerta.descripcion"></p>
                                    <div class="mt-3 pt-2 border-t border-slate-200 flex items-center justify-between">
                                        <span class="text-[10px] text-slate-400 uppercase font-black">Nivel: <span x-text="alerta.nivel"></span></span>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="alerts.length === 0 && baseErrors > 0">
                                <div class="text-center py-10">
                                    <p class="text-sm text-slate-500 font-bold">Hay errores reportados en el día, pero no se han capturado alertas nuevas en esta sesión de monitoreo.</p>
                                </div>
                            </template>
                            <template x-if="alerts.length === 0 && baseErrors === 0">
                                <div class="text-center py-10">
                                    <p class="text-sm text-slate-500 font-bold">No hay alertas críticas en el registro.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-200">
                        <button @click="clearAlerts(); showModal = false" type="button" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-rose-600 text-base font-medium text-white hover:bg-rose-700 sm:ml-3 sm:w-auto sm:text-sm transition uppercase tracking-widest font-bold">
                            Limpiar Registro
                        </button>
                        <button @click="showModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest font-bold">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         MÓDULO JAVASCRIPT: WEBCHANNELS + UNIFIED ENGINE POLLING (ANTI-F5)
         ═══════════════════════════════════════════════════════════════ --}}
    @push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            // El listener de Alpine JS se encarga ahora del evento 'nueva-alerta-global'
            // Inicializar el motor de Polling unificado cada 15 segundos
            setInterval(executeUnifiedPolling, 15000);
        });

        /**
         * Motor unificado encargado de sincronizar el estado de FreePBX (API)
         * y refrescar asíncronamente los nodos del DOM del Dashboard.
         */
        function executeUnifiedPolling() {
            // 1. Sincronizar el Estado de la API de FreePBX (Mantiene tu lógica nativa)
            fetch('/pbx/status', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                updatePbxStatusCard(data);
            })
            .catch(err => console.error("Error en Health Check de PBX:", err));

            // 2. Component Polling via DOMParser (Solución al F5)
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Lista de contenedores clave a mutar de forma transparente
                    const targets = [
                        'productivity-banner',
                        'kpi-cards-grid',
                        'tabla-operadores-container',
                        'live-feed-container'
                    ];

                    targets.forEach(id => {
                        const oldElement = document.getElementById(id);
                        const newElement = doc.getElementById(id);
                        
                        if (oldElement && newElement) {
                            oldElement.innerHTML = newElement.innerHTML;
                        }
                    });

                    console.log("Sincronización asíncrona del Dashboard completada con éxito.");
                })
                .catch(err => console.error("Error ejecutando Polling del Dashboard:", err));
        }

        function updatePbxStatusCard(data) {
            const card    = document.getElementById('pbx-status-card');
            const text    = document.getElementById('pbx-status-text');
            const dot     = document.getElementById('pbx-status-dot');
            const subtext = document.getElementById('pbx-status-subtext');

            if (!card || !text) return;

            if (data.connected) {
                card.classList.remove('bg-rose-700');
                card.classList.add('bg-emerald-700');
                text.innerText = 'Conectado';
                if (dot) dot.className = 'w-2.5 h-2.5 rounded-full bg-white animate-pulse';
                if (subtext) subtext.innerText = 'En línea';
            } else {
                if (text.innerText.toUpperCase() === 'CONECTADO') {
                    // Despachar evento para que Alpine lo capture
                    window.dispatchEvent(new CustomEvent('nueva-alerta-global', {
                        detail: {
                            nivel: 'CRITICAL',
                            tipo_alerta: 'API PBX CAÍDA',
                            descripcion: 'Se perdió conexión con Asterisk/FreePBX.'
                        }
                    }));
                }
                card.classList.remove('bg-emerald-700');
                card.classList.add('bg-rose-700');
                text.innerText = 'Desconectado';
                if (dot) dot.className = 'w-2.5 h-2.5 rounded-full bg-white/40';
                if (subtext) subtext.innerText = 'Fuera de línea';
            }
        }



    </script>
    @endpush
</x-app-layout>