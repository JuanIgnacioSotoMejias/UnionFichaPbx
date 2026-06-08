<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Panel de Control — PBX Receptor
            </h2>
            <span class="text-xs text-gray-400">Actualizado: {{ now()->format('d/m/Y H:i:s') }}</span>
        </div>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ══════════════════════════════════════════════════════
             FILA 1: Tarjetas de métricas
        ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Estado AMI --}}
            <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center
                    {{ $amiConectado || $dryRun ? 'bg-green-100' : 'bg-red-100' }}">
                    <svg class="w-6 h-6 {{ $amiConectado || $dryRun ? 'text-green-600' : 'text-red-600' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905
                                 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">FreePBX AMI</p>
                    @if($dryRun)
                        <p class="text-sm font-bold text-yellow-600">● DRY-RUN</p>
                    @elseif($amiConectado)
                        <p class="text-sm font-bold text-green-600">● Conectado</p>
                    @else
                        <p class="text-sm font-bold text-red-600">● Sin Conexión</p>
                    @endif
                    <p class="text-xs text-gray-400 truncate max-w-[140px]" title="{{ $amiMensaje }}">{{ $amiMensaje }}</p>
                </div>
            </div>

            {{-- Operadores activos --}}
            <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                 M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0
                                 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">En Cola Ahora</p>
                    <p class="text-2xl font-bold text-blue-700">{{ $operadoresActivos }}</p>
                    <p class="text-xs text-gray-400">de {{ $totalOperadores }} registrados</p>
                </div>
            </div>

            {{-- Eventos hoy --}}
            <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Eventos Hoy</p>
                    <p class="text-2xl font-bold text-indigo-700">{{ $totalEventosHoy }}</p>
                    <p class="text-xs text-gray-400">login / logout</p>
                </div>
            </div>

            {{-- Errores hoy --}}
            <div class="bg-white rounded-xl shadow-sm border p-5 flex items-center gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full {{ $erroresHoy > 0 ? 'bg-red-100' : 'bg-gray-100' }}
                     flex items-center justify-center">
                    <svg class="w-6 h-6 {{ $erroresHoy > 0 ? 'text-red-600' : 'text-gray-400' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3
                                 L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide font-medium">Errores Hoy</p>
                    <p class="text-2xl font-bold {{ $erroresHoy > 0 ? 'text-red-600' : 'text-gray-500' }}">{{ $erroresHoy }}</p>
                    <p class="text-xs text-gray-400">en API receptor</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             FILA 2: Tabla operadores + Últimos eventos
        ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Tabla de operadores (2/3) --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Operadores Registrados</h3>
                    <a href="{{ route('operadores.create') }}"
                       class="text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-3 py-1.5 rounded-lg transition">
                        + Nuevo
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-left">Operador</th>
                                <th class="px-4 py-3 text-left">Usuario Ficha</th>
                                <th class="px-4 py-3 text-left">Extensión</th>
                                <th class="px-4 py-3 text-left">Cola</th>
                                <th class="px-4 py-3 text-center">Estado</th>
                                <th class="px-4 py-3 text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($operadores as $op)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium text-gray-800">{{ $op->nombre_operador }}</td>
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $op->ficha_username }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-gray-100 text-gray-700 font-mono text-xs px-2 py-0.5 rounded">
                                        {{ $op->extension }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $op->queue_name }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($op->is_active)
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span> En Cola
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-500 text-xs font-semibold px-2 py-0.5 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('operadores.edit', $op) }}"
                                       class="text-xs text-indigo-600 hover:underline">Editar</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    No hay operadores registrados.
                                    <a href="{{ route('operadores.create') }}" class="text-indigo-600 hover:underline ml-1">Registrar el primero</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Últimos eventos (1/3) --}}
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="px-5 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Últimos Eventos</h3>
                </div>
                <ul class="divide-y divide-gray-100 text-sm max-h-96 overflow-y-auto">
                    @forelse($ultimosEventos as $ev)
                    <li class="px-4 py-3 flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $ev->evento === 'LOGIN' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                            {{ $ev->evento === 'LOGIN' ? '▶' : '■' }}
                        </span>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-700 truncate">
                                {{ $ev->operador?->nombre_operador ?? '—' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $ev->evento }} · Ext. {{ $ev->operador?->extension ?? '?' }}
                            </p>
                            <p class="text-xs text-gray-300">{{ \Carbon\Carbon::parse($ev->created_at)->diffForHumans() }}</p>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-8 text-center text-gray-400 text-sm">Sin eventos registrados hoy.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════
             FILA 3: Errores AMI recientes + Info endpoint
        ══════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Errores AMI --}}
            <div class="bg-white rounded-xl shadow-sm border">
                <div class="px-5 py-4 border-b">
                    <h3 class="font-semibold text-gray-800">Últimos Errores AMI</h3>
                </div>
                @if($ultimosErroresAmi->isEmpty())
                <p class="px-5 py-6 text-sm text-gray-400 text-center">✓ Sin errores AMI recientes.</p>
                @else
                <ul class="divide-y divide-gray-100 text-sm">
                    @foreach($ultimosErroresAmi as $err)
                    <li class="px-4 py-3">
                        <div class="flex items-center justify-between mb-0.5">
                            <span class="font-mono text-xs bg-red-100 text-red-700 px-1.5 py-0.5 rounded">{{ $err->comando_enviado }}</span>
                            <span class="text-xs text-gray-400">Ext. {{ $err->extension }}</span>
                        </div>
                        <p class="text-xs text-gray-500 truncate">{{ $err->respuesta_asterisk }}</p>
                        <p class="text-xs text-gray-300">{{ $err->created_at->diffForHumans() }}</p>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>

            {{-- Info del endpoint API --}}
            <div class="bg-gray-900 text-green-400 rounded-xl shadow-sm p-5 font-mono text-xs space-y-3">
                <p class="text-gray-400 uppercase tracking-widest text-xs mb-2">Endpoint API para Ficha</p>

                <div>
                    <span class="text-yellow-400">POST</span>
                    <span class="text-white ml-2">{{ url('/api/sesion') }}</span>
                </div>
                <div class="bg-gray-800 rounded p-3 space-y-1">
                    <p class="text-gray-400">// Headers requeridos</p>
                    <p><span class="text-blue-400">Authorization:</span> Bearer <span class="text-orange-400">{token}</span></p>
                    <p><span class="text-blue-400">Content-Type:</span> application/json</p>
                </div>
                <div class="bg-gray-800 rounded p-3 space-y-1">
                    <p class="text-gray-400">// Body (JSON)</p>
                    <p>{</p>
                    <p class="ml-3"><span class="text-green-300">"usuario"</span>: <span class="text-orange-300">"jperez"</span>,</p>
                    <p class="ml-3"><span class="text-green-300">"evento"</span>:  <span class="text-orange-300">"LOGIN"</span> | <span class="text-orange-300">"LOGOUT"</span></p>
                    <p>}</p>
                </div>
                <div>
                    <span class="text-yellow-400">GET</span>
                    <span class="text-white ml-2">{{ url('/api/status') }}</span>
                    <span class="text-gray-500 ml-2">// health-check</span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
