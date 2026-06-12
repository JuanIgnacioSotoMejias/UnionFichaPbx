<x-app-layout>
    {{-- Usamos x-data para controlar el estado del modal --}}
    <div x-data="{ showCreateModal: false }" class="py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        
        {{-- Alertas de Sistema --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <span class="block sm:inline font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <span class="block sm:inline font-bold">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Errores de validación --}}
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl relative shadow-sm" role="alert">
                <span class="block font-black uppercase tracking-tighter mb-1">Se encontraron los siguientes errores:</span>
                <ul class="text-xs font-bold space-y-1 list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_4px_20px_-5px_rgba(15,23,42,0.4)] overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 bg-slate-900 border-b border-slate-800">
                <h3 class="font-black text-white uppercase italic tracking-tighter">Usuarios Administradores</h3>
                
                {{-- Botón que activa el modal --}}
                @can('manage-system')
                <button @click="showCreateModal = true"
                   class="text-xs bg-green-600 hover:bg-green-700 text-white font-black uppercase px-4 py-2 rounded-lg transition shadow-md hover:scale-[1.02] active:scale-[0.98]">
                    + Nuevo Usuario
                </button>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-900 text-white uppercase text-xs font-semibold tracking-wider">
                        <tr class="border-b border-slate-800">
                            <th class="px-6 py-4 text-left">Nombre</th>
                            <th class="px-6 py-4 text-left">Correo Electrónico</th>
                            @can('manage-system')
                            <th class="px-6 py-4 text-left">Auditoría de Sesión</th>
                            @endcan
                            <th class="px-6 py-4 text-center">Rol</th>
                            @can('manage-system')
                            <th class="px-6 py-4 text-center">Acciones</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($users as $user)
                        <tr class="hover:bg-slate-50 transition even:bg-slate-50/50 odd:bg-white">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-500 font-medium">{{ $user->email }}</td>
                            @can('manage-system')
                            <td class="px-6 py-4 text-slate-500 text-[11px] space-y-1">
                                <div><strong class="text-slate-700">IP:</strong> {{ $user->last_login_ip ?? 'N/A' }}</div>
                                <div class="text-emerald-600"><strong class="text-slate-700">In:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
                                <div class="text-amber-600"><strong class="text-slate-700">Out:</strong> {{ $user->last_logout_at ? $user->last_logout_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
                            </td>
                            @endcan
                            <td class="px-6 py-4 text-center">
                                <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border {{ $user->isAdmin() ? 'bg-blue-100 text-blue-800 border-blue-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ $user->isAdmin() ? 'Administrador' : 'Usuario' }}
                                </span>
                                @if(!$user->is_active)
                                <span class="ml-2 text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full border bg-red-100 text-red-800 border-red-200">
                                    Inactivo
                                </span>
                                @endif
                            </td>
                            @can('manage-system')
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center items-center gap-2">
                                    {{-- Bloqueo de Super Administrador (ID 1) --}}
                                    @if($user->id !== 1)
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="inline-flex items-center bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition border border-blue-200/50">
                                            Editar
                                        </a>
                                        
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas {{ $user->is_active ? 'deshabilitar' : 'habilitar' }} este usuario?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-black uppercase tracking-wider transition border {{ $user->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-700 border-amber-200/50' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-200/50' }}">
                                                {{ $user->is_active ? 'Deshabilitar' : 'Habilitar' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg">Protegido</span>
                                    @endif
                                </div>
                            </td>
                            @endcan
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($users->hasPages())
                <div class="p-4 bg-slate-50 border-t border-slate-200">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

       {{-- MODAL PARA NUEVO USUARIO --}}
<div x-show="showCreateModal" 
     x-data="{ confirming: false }"
     class="fixed inset-0 z-50 overflow-y-auto" 
     x-cloak>
    
    <div class="flex items-center justify-center min-h-screen px-4">
        {{-- Overlay --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showCreateModal = false; confirming = false"></div>

        {{-- Contenido del Modal --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-lg w-full z-10 transform transition-all border border-slate-300">
            
            {{-- Encabezado dinámico corporativo --}}
            <div :class="confirming ? 'bg-amber-600' : 'bg-[#1e293b]'" class="px-6 py-4 flex justify-between items-center transition-colors duration-300">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors duration-300" :class="confirming ? 'bg-white/20' : 'bg-blue-500/20'">
                        <template x-if="!confirming">
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </template>
                        <template x-if="confirming">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </template>
                    </div>
                    <h3 class="text-white font-black uppercase italic tracking-tighter">
                        <span x-show="!confirming">Registrar Nuevo Usuario</span>
                        <span x-show="confirming">Confirmar Registro</span>
                    </h3>
                </div>
                <button @click="showCreateModal = false; confirming = false" class="text-white/40 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- El contenido del form empieza directo --}}

            {{-- Formulario --}}
            <form id="form-nuevo-usuario" action="{{ route('users.store') }}" method="POST" 
                  x-on:submit="$el.querySelector('button[type=submit]').disabled = true; $el.querySelector('button[type=submit]').innerHTML = 'Guardando...'">
                @csrf
                
                {{-- PASO 1: Ingreso de Datos (usamos clase CSS en vez de x-show para que los inputs no desaparezcan del DOM) --}}
                <div :class="confirming ? 'hidden' : ''" class="p-6 space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest">Nombre Completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm font-bold text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest">Cédula de Identidad</label>
                        <input type="text" name="cedula" value="{{ old('cedula') }}" placeholder="V-12345678" required class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm font-bold text-slate-700">
                        <span class="text-[10px] text-slate-400">Formato: V-12345678, E-87654321, J-123456789</span>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm font-bold text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest">Rol del Sistema</label>
                        <select name="role" required class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm font-bold text-slate-700">
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuario (Solo Consulta)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador (Control Total)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest">Contraseña</label>
                            <input type="password" name="password" required class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase mb-1 tracking-widest">Confirmar</label>
                            <input type="password" name="password_confirmation" required class="w-full border-slate-200 rounded-lg focus:ring-blue-600 text-sm">
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 uppercase hover:text-slate-800">
                            Cancelar
                        </button>
                        <button type="button" @click="confirming = true" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-black uppercase text-xs shadow-lg transition-all">
                            Continuar
                        </button>
                    </div>
                </div>

                {{-- PASO 2: Confirmación Final --}}
                <div x-show="confirming" x-transition class="p-8 text-center space-y-6">
                    <div class="w-20 h-20 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto shadow-inner">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    
                    <div>
                        <h4 class="text-lg font-black text-slate-800 uppercase italic">¿Estás seguro del registro?</h4>
                        <p class="text-sm text-slate-500 mt-2">Verifica que los datos del nuevo usuario sean correctos antes de proceder al guardado.</p>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-black uppercase text-sm shadow-xl transition-all">
                            Sí, Guardar Usuario
                        </button>
                        <button type="button" @click="confirming = false" class="text-xs font-bold text-slate-400 uppercase hover:text-slate-600 transition">
                            No, volver a revisar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script para reabrir modal si hubo errores de validación --}}
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const rootEl = document.querySelector('[x-data*="showCreateModal"]');
            if (rootEl) {
                rootEl.__x.$data.showCreateModal = true;
            }
        }, 100);
    });
</script>
@endif
</x-app-layout>