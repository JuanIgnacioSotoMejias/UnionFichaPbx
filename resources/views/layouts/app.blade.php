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
                {{-- Notificaciones Globales --}}
                <div x-data="{
                    open: false,
                    notificaciones: [],
                    unreadCount: 0,
                    addNotification(notif) {
                        this.notificaciones.unshift(notif);
                        if(this.notificaciones.length > 20) this.notificaciones.pop();
                        this.unreadCount++;
                    },
                    markAsRead() {
                        this.unreadCount = 0;
                    }
                }"
                @nueva-alerta-global.window="addNotification($event.detail)"
                class="relative">
                    <button @click="open = !open; markAsRead()" class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span x-show="unreadCount > 0" class="absolute top-1 right-1 flex items-center justify-center w-4 h-4 text-[9px] font-bold text-white bg-red-600 rounded-full" x-text="unreadCount" style="display: none;"></span>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" x-transition style="display: none;" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-2xl border border-slate-200 z-50 overflow-hidden">
                        <div class="bg-slate-900 px-4 py-3 flex justify-between items-center">
                            <h3 class="text-sm font-black text-white uppercase tracking-tighter">Notificaciones</h3>
                            <span class="text-xs text-slate-400 font-mono" x-text="notificaciones.length"></span>
                        </div>
                        <div class="max-h-96 overflow-y-auto">
                            <template x-if="notificaciones.length === 0">
                                <div class="p-6 text-center text-slate-400 text-sm font-medium italic">
                                    No hay notificaciones recientes
                                </div>
                            </template>
                            <template x-for="(notif, index) in notificaciones" :key="index">
                                <div class="p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" :class="notif.nivel === 'CRITICAL' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600'">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-black uppercase text-slate-800" x-text="notif.tipo_alerta"></p>
                                            <p class="text-[11px] text-slate-500 mt-0.5 leading-tight" x-text="notif.descripcion"></p>
                                            <p class="text-[9px] text-slate-400 mt-1 font-mono" x-text="notif.hora"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

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
            const clock = document.getElementById('real-time-clock');
            if(clock) clock.innerText = now.toLocaleTimeString('en-GB');
        }, 1000);

        /**
         * Toast flotante global de advertencia
         */
        function showGlobalToast(titulo, descripcion, nivel = 'WARNING') {
            let toastContainer = document.getElementById('global-toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'global-toast-container';
                toastContainer.className = 'fixed bottom-6 right-6 z-[9999] space-y-3 max-w-sm';
                document.body.appendChild(toastContainer);
            }

            const isCritical = nivel === 'CRITICAL';
            const colorClass = isCritical ? 'red' : 'amber';

            const toast = document.createElement('div');
            toast.className = 'transform translate-x-full opacity-0 transition-all duration-500 ease-out';
            toast.innerHTML = `
                <div class="bg-white border-l-4 border-${colorClass}-500 rounded-xl shadow-2xl p-4 flex items-start gap-3 ring-1 ring-slate-200">
                    <div class="flex-shrink-0 w-10 h-10 bg-${colorClass}-100 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-${colorClass}-600 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-slate-800 uppercase tracking-wide">${titulo}</p>
                        <p class="text-[11px] text-slate-500 mt-1 leading-tight">${descripcion}</p>
                        <p class="text-[9px] text-slate-400 mt-2 font-mono">${new Date().toLocaleTimeString()}</p>
                    </div>
                    <button onclick="this.closest('.transform').remove()" class="text-slate-400 hover:text-slate-600 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;

            toastContainer.appendChild(toast);
            requestAnimationFrame(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            });
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 8000);
        }
    </script>
    @stack('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Echo) {
                window.Echo.channel('alertas')
                    .listen('.alerta.nueva', (e) => {
                        window.dispatchEvent(new CustomEvent('nueva-alerta-global', { 
                            detail: {
                                nivel: e.alerta.nivel || 'WARNING',
                                tipo_alerta: e.alerta.tipo_alerta,
                                descripcion: e.alerta.descripcion,
                                hora: new Date().toLocaleTimeString()
                            }
                        }));
                        showGlobalToast(e.alerta.tipo_alerta, e.alerta.descripcion, e.alerta.nivel);
                    })
                    .listen('.extension.offline', (e) => {
                        window.dispatchEvent(new CustomEvent('nueva-alerta-global', { 
                            detail: {
                                nivel: e.nivel || 'WARNING',
                                tipo_alerta: e.tipo_alerta || 'EXTENSIÓN OFFLINE',
                                descripcion: e.descripcion || `Ext. ${e.extension} caída.`,
                                hora: new Date().toLocaleTimeString()
                            }
                        }));
                        showGlobalToast(`Extensión ${e.extension} Offline`, e.descripcion, 'WARNING');
                    });
            }
        });
    </script>
</body>