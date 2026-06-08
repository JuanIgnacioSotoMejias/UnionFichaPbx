<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
  <body class="font-sans antialiased bg-gray-100">

    {{-- Sidebar fijo (posicionamiento fixed, no necesita contenedor flex) --}}
    @include('layouts.navigation')

    {{-- Contenido principal desplazado 16rem (w-64) para no quedar bajo el sidebar --}}
    <div class="ml-64 flex flex-col min-h-screen">

        <header class="bg-white h-16 shadow-sm border-b-4 border-red-600 flex items-center justify-between px-8 z-10 sticky top-0">
            <div class="flex items-center gap-4">
                <h2 class="font-black text-xl text-slate-800 uppercase tracking-tighter italic">
                    PBX Receptor — Monitoreo
                </h2>
                <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-1 rounded font-mono uppercase tracking-widest">Turno: {{ now()->format('d/m/Y') }}</span>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block border-r pr-6 border-slate-200">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reloj de Estación</p>
                    <p class="text-sm font-black text-slate-700 font-mono" id="real-time-clock">{{ now()->format('H:i:s') }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs font-black text-slate-800 leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest">Supervisor</p>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-full hover:bg-red-600 hover:text-white transition-all shadow-sm group" title="Cerrar Sesión">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

    {{-- Modal Global de Documentación de API --}}
    <x-modal name="api-docs" maxWidth="2xl">
        <div class="bg-slate-900 text-white p-6 space-y-6">
            <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                <h3 class="text-lg font-black uppercase tracking-tight italic text-emerald-400 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Documentación Técnica de la API
                </h3>
                <button @click="$dispatch('close-modal', 'api-docs')" class="text-slate-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="space-y-4 text-sm leading-relaxed overflow-y-auto max-h-[60vh] pr-2">
                <p class="text-slate-300">
                    El sistema PBX Receptor expone endpoints seguros para sincronizar los eventos de inicio y fin de turno de los operadores en las colas de llamadas dinámicas.
                </p>

                <div class="bg-slate-950 rounded-xl border border-slate-800 p-4 space-y-3 font-mono text-xs">
                    <p class="text-slate-400 uppercase tracking-widest text-xs mb-2 border-b border-slate-800 pb-1 font-bold">1. Sesión de Operador (Login/Logout)</p>

                    <div class="flex items-center gap-2">
                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded text-[10px] font-bold">POST</span>
                        <span class="text-slate-200">{{ url('/api/sesion') }}</span>
                    </div>
                    
                    <div class="bg-slate-900 rounded-lg p-3 space-y-1 text-slate-300 border border-slate-800">
                        <p class="text-slate-500">// Headers requeridos</p>
                        <p><span class="text-blue-400">Authorization:</span> Bearer <span class="text-orange-400">{RECEPTOR_TOKEN}</span></p>
                        <p><span class="text-blue-400">Content-Type:</span> application/json</p>
                    </div>

                    <div class="bg-slate-900 rounded-lg p-3 space-y-1 text-slate-300 border border-slate-800">
                        <p class="text-slate-500">// Body (JSON)</p>
                        <p class="text-slate-400">{</p>
                        <p class="ml-4"><span class="text-emerald-400">"usuario"</span>: <span class="text-orange-300">"jperez"</span>,</p>
                        <p class="ml-4"><span class="text-emerald-400">"evento"</span>: <span class="text-orange-300">"LOGIN"</span> <span class="text-slate-500">|</span> <span class="text-orange-300">"LOGOUT"</span></p>
                        <p class="text-slate-400">}</p>
                    </div>
                </div>

                <div class="bg-slate-950 rounded-xl border border-slate-800 p-4 space-y-3 font-mono text-xs">
                    <p class="text-slate-400 uppercase tracking-widest text-xs mb-2 border-b border-slate-800 pb-1 font-bold">2. Cambio de Extensión en Caliente</p>
                    <div class="flex items-center gap-2">
                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 px-2 py-0.5 rounded text-[10px] font-bold">POST</span>
                        <span class="text-slate-200">{{ url('/api/operadores/{id}/cambiar-extension') }}</span>
                    </div>
                    <div class="bg-slate-900 rounded-lg p-3 space-y-1 text-slate-300 border border-slate-800">
                        <p class="text-slate-500">// Body (JSON)</p>
                        <p class="text-slate-400">{</p>
                        <p class="ml-4"><span class="text-emerald-400">"extension"</span>: <span class="text-orange-300">"8005"</span></p>
                        <p class="text-slate-400">}</p>
                    </div>
                </div>

                <div class="bg-slate-950 rounded-xl border border-slate-800 p-4 space-y-3 font-mono text-xs">
                    <p class="text-slate-400 uppercase tracking-widest text-xs mb-2 border-b border-slate-800 pb-1 font-bold">3. Health Check y Monitoreo</p>

                    <div class="flex items-center gap-2 mb-2">
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded text-[10px] font-bold">GET</span>
                        <span class="text-slate-200">{{ url('/api/status') }}</span>
                        <span class="text-slate-500 text-[10px]">// Estado general AMI</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-2 py-0.5 rounded text-[10px] font-bold">GET</span>
                        <span class="text-slate-200">{{ url('/api/ping') }}</span>
                        <span class="text-slate-500 text-[10px]">// Ping público</span>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-4 flex justify-end">
                <button @click="$dispatch('close-modal', 'api-docs')" class="bg-slate-800 hover:bg-slate-700 text-white font-bold uppercase text-xs px-6 py-2.5 rounded-lg transition-all active:scale-[0.97]">
                    Cerrar
                </button>
            </div>
        </div>
    </x-modal>

    <script>
        setInterval(() => {
            const now = new Date();
            document.getElementById('real-time-clock').innerText = now.toLocaleTimeString('en-GB');
        }, 1000);
    </script>
</body>