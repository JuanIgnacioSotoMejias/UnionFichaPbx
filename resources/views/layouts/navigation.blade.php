<nav x-data="{ open: false }" class="fixed top-0 left-0 h-screen w-64 bg-slate-900 flex flex-col flex-shrink-0 border-r border-slate-800 z-50 overflow-hidden">
    <div class="py-4 px-6">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg mb-2">
                 <x-application-logo class="w-12 h-auto" />
            </div>
            <span class="text-white font-black text-sm tracking-widest text-center uppercase">VEN 911</span>
        </a>
    </div>

    {{-- Enlaces de navegación con pl-4 y pr-0 para fusionar el estado activo --}}
    <div class="mt-2 pl-4 pr-0 space-y-1">
        <p class="text-[10px] font-bold text-slate-500 uppercase px-3 mb-1 tracking-widest text-center">Navegación</p>
        
        @php
            $isDashboard = request()->routeIs('dashboard');
        @endphp
        <a href="{{ route('dashboard') }}" 
           class="{{ $isDashboard 
                    ? 'flex items-center justify-center py-2.5 px-4 bg-gray-100 text-gray-900 border-b border-slate-700 font-semibold rounded-l-lg -mr-px relative z-10 shadow-sm' 
                    : 'flex items-center justify-center py-2.5 px-4 text-slate-300 hover:bg-slate-800 border-b border-slate-700/50 rounded-l-lg transition-colors' }}">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span class="font-bold text-sm uppercase">Panel Principal</span>
        </a>

        @if(auth()->user() && auth()->user()->isAdmin())
            @php
                $isUsers = request()->routeIs('users.*');
            @endphp
            <a href="{{ route('users.index') }}" 
               class="{{ $isUsers 
                        ? 'flex items-center justify-center py-2.5 px-4 bg-gray-100 text-gray-900 border-b border-slate-700 font-semibold rounded-l-lg -mr-px relative z-10 shadow-sm' 
                        : 'flex items-center justify-center py-2.5 px-4 text-slate-300 hover:bg-slate-800 border-b border-slate-700/50 rounded-l-lg transition-colors' }}">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span class="font-bold text-sm uppercase">Usuarios</span>
            </a>

            @php
                $isOperadores = request()->routeIs('operadores.*');
            @endphp
            <a href="{{ route('operadores.index') }}" 
               class="{{ $isOperadores 
                        ? 'flex items-center justify-center py-2.5 px-4 bg-gray-100 text-gray-900 border-b border-slate-700 font-semibold rounded-l-lg -mr-px relative z-10 shadow-sm' 
                        : 'flex items-center justify-center py-2.5 px-4 text-slate-300 hover:bg-slate-800 border-b border-slate-700/50 rounded-l-lg transition-colors' }}">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="font-bold text-sm uppercase">Personal</span>
            </a>

            @php
                $isExtensions = request()->routeIs('extensions.*');
            @endphp
            <a href="{{ route('extensions.index') }}" 
               class="{{ $isExtensions 
                        ? 'flex items-center justify-center py-2.5 px-4 bg-gray-100 text-gray-900 border-b border-slate-700 font-semibold rounded-l-lg -mr-px relative z-10 shadow-sm' 
                        : 'flex items-center justify-center py-2.5 px-4 text-slate-300 hover:bg-slate-800 border-b border-slate-700/50 rounded-l-lg transition-colors' }}">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span class="font-bold text-sm uppercase">Extensiones</span>
            </a>
        @endif

        {{-- Botón para ver API colapsado --}}
        <button @click="$dispatch('open-modal', 'api-docs')" class="w-full flex items-center justify-center py-2.5 px-4 text-slate-300 hover:bg-slate-800 border-b border-slate-700/50 rounded-l-lg transition-colors">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
            <span class="font-bold text-sm uppercase">DOCUMENTACIÓN API</span>
        </button>
    </div>

    {{-- Pie del sidebar: Bandera de Venezuela + Estado del servidor en fila --}}
    <div class="mt-auto p-4 border-t border-slate-700">
        <div class="flex items-center gap-3">
            {{-- Bandera pequeña como insignia --}}
            <img src="{{ asset('img/ve.png') }}"
                 alt="VE"
                 class="w-8 h-auto object-contain rounded shadow-sm flex-shrink-0"
                 title="República Bolivariana de Venezuela">

            {{-- Indicador de servidor activo --}}
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse flex-shrink-0"></span>
                <span class="text-xs text-slate-300 uppercase tracking-wider font-semibold">Servidor Activo</span>
            </div>
        </div>
    </div>
</nav>