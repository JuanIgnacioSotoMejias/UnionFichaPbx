<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Cabecera --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Reporte General del Sistema</h2>
                <p class="text-xs text-slate-400 mt-1">Métricas globales y estadísticas de uso de la central telefónica.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reportes.especifico') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-200 transition-all hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Ver Reportes Específicos
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            {{-- Tarjetas de Métricas (Lado Izquierdo) --}}
            <div class="lg:col-span-4 flex flex-col gap-4">
                {{-- Total Llamadas --}}
                <div class="group relative overflow-hidden rounded-2xl p-5 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 bg-blue-600 flex-1 flex flex-col justify-between">
                    <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                    <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">Total Llamadas</p>
                            <p class="text-3xl font-black text-white leading-none">{{ $metricasGenerales['total_llamadas'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                    </div>
                    
                    <div class="relative mt-4 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-white/40"></span>
                        <span class="text-[10px] font-bold text-white/80 uppercase tracking-wider">Acumulado global</span>
                    </div>
                </div>

                {{-- AHT Promedio --}}
                <div class="group relative overflow-hidden rounded-2xl p-5 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 bg-amber-500 flex-1 flex flex-col justify-between">
                    <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                    <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">AHT Promedio</p>
                            <p class="text-3xl font-black text-white leading-none">{{ $metricasGenerales['aht_promedio'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    
                    <div class="relative mt-4 flex items-center gap-2">
                        @if(isset($metricasGenerales['telemetria_pendiente']) && $metricasGenerales['telemetria_pendiente'])
                            <span class="bg-amber-600 text-white px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border border-amber-400">⚠️ Datos próximos a actualización</span>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-white animate-pulse"></span>
                            <span class="text-[10px] font-bold text-white/80 uppercase tracking-wider">Métricas en tiempo real</span>
                        @endif
                    </div>
                </div>

                {{-- Ocupación General --}}
                <div class="group relative overflow-hidden rounded-2xl p-5 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 bg-emerald-600 flex-1 flex flex-col justify-between">
                    <div class="absolute -right-4 -top-4 w-24 h-24 rounded-full bg-white/10"></div>
                    <div class="absolute -right-2 -top-2 w-14 h-14 rounded-full bg-white/10"></div>
                    
                    <div class="relative flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold text-white/70 uppercase tracking-widest mb-1">Ocupación General</p>
                            <p class="text-3xl font-black text-white leading-none">{{ $metricasGenerales['ocupacion_general'] }}%</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    
                    <div class="relative mt-4 flex items-center gap-2 w-full">
                        @if(isset($metricasGenerales['telemetria_pendiente']) && $metricasGenerales['telemetria_pendiente'])
                            <span class="bg-emerald-700 text-white px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider border border-emerald-400">⚠️ Datos próximos a actualización</span>
                        @else
                            <div class="w-full bg-white/20 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-white h-1.5 rounded-full transition-all duration-700" style="width: {{ $metricasGenerales['ocupacion_general'] }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-white/80 uppercase tracking-wider ml-2">{{ $metricasGenerales['ocupacion_general'] }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Panel de Gráficos (Lado Derecho) --}}
            <div class="lg:col-span-8 flex flex-col h-full">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden h-full flex flex-col flex-1">
                    <div class="bg-slate-900 px-6 py-4 border-b border-slate-800">
                        <h3 class="font-black text-white uppercase tracking-tighter italic text-sm">Resumen de Actividad Mensual</h3>
                    </div>
                    <div class="p-10 flex-grow flex flex-col items-center justify-center min-h-[400px]">
                        <svg class="w-16 h-16 text-slate-200 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        <p class="text-slate-400 font-bold text-sm uppercase tracking-widest text-center">Gráficos de actividad próximamente</p>
                        <p class="text-slate-400 text-xs text-center mt-1">El módulo de gráficas avanzadas estará disponible en futuras actualizaciones.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
