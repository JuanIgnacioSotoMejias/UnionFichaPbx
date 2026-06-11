<x-app-layout>
    <div class="p-6 space-y-6">

        {{-- Cabecera --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Reportes Específicos</h2>
                <p class="text-xs text-slate-400 mt-1">Filtra y visualiza información detallada del sistema.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reportes.index') }}" class="inline-flex items-center gap-2 bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver a General
                </a>
            </div>
        </div>

        {{-- Contenedor Principal --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden">
            
            {{-- Filtros --}}
            <div class="bg-slate-900 px-6 py-4 border-b border-slate-800">
                <form action="{{ route('reportes.especifico') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="w-full sm:w-auto flex-1">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tipo de Reporte</label>
                        <select name="tipo" onchange="this.form.submit()" class="w-full border-slate-700 bg-slate-800 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm font-bold">
                            <option value="operador" {{ $tipoReporte == 'operador' ? 'selected' : '' }}>Por Operador</option>
                            <option value="extension" {{ $tipoReporte == 'extension' ? 'selected' : '' }}>Por Extensión</option>
                            <option value="fechas" {{ $tipoReporte == 'fechas' ? 'selected' : '' }}>Por Fechas (Logins/Logouts)</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-lg shadow-blue-500/20 transition-all">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Resultados --}}
            <div class="p-6">
                @if($tipoReporte == 'operador')
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight mb-4">Listado de Operadores para Reporte</h3>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-black tracking-widest">
                                <tr>
                                    <th class="px-6 py-3 border-b border-slate-200">Operador</th>
                                    <th class="px-6 py-3 border-b border-slate-200 text-center">Ficha Username</th>
                                    <th class="px-6 py-3 border-b border-slate-200 text-center">Estado</th>
                                    <th class="px-6 py-3 border-b border-slate-200 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($datosEspecificos['operadores'] as $op)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-3 text-sm font-bold text-slate-800">{{ $op->nombre_operador }}</td>
                                    <td class="px-6 py-3 text-center"><span class="bg-slate-100 text-slate-500 px-2 py-1 rounded text-xs font-mono">{{ $op->ficha_username }}</span></td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest {{ $op->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $op->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <a href="{{ route('reportes.exportar.operador', $op->id) }}" target="_blank" class="inline-block text-xs bg-blue-50 text-blue-600 font-bold px-3 py-1.5 rounded hover:bg-blue-100 transition uppercase">Generar PDF</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm italic">No hay operadores registrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($tipoReporte == 'extension')
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight mb-4">Listado de Extensiones para Reporte</h3>
                    <div class="overflow-x-auto border border-slate-200 rounded-xl">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-slate-600 uppercase text-[10px] font-black tracking-widest">
                                <tr>
                                    <th class="px-6 py-3 border-b border-slate-200">Extensión</th>
                                    <th class="px-6 py-3 border-b border-slate-200 text-center">Descripción</th>
                                    <th class="px-6 py-3 border-b border-slate-200 text-center">Estado</th>
                                    <th class="px-6 py-3 border-b border-slate-200 text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($datosEspecificos['extensiones'] as $ext)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-3 text-sm font-bold text-slate-800 font-mono">{{ $ext->numero }}</td>
                                    <td class="px-6 py-3 text-center text-sm text-slate-600">{{ $ext->descripcion ?? 'N/A' }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest {{ $ext->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $ext->is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <a href="{{ route('reportes.exportar.extension', $ext->id) }}" target="_blank" class="inline-block text-xs bg-blue-50 text-blue-600 font-bold px-3 py-1.5 rounded hover:bg-blue-100 transition uppercase">Generar PDF</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm italic">No hay extensiones registradas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @elseif($tipoReporte == 'fechas')
                    <div class="flex flex-col items-center justify-center min-h-[250px] text-center">
                        <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Filtro de Fechas (En Desarrollo)</h3>
                        <p class="text-xs text-slate-400 mt-2 max-w-md">La búsqueda de eventos y logs por rangos de fechas estará disponible en el próximo despliegue del módulo de reportes avanzados.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
