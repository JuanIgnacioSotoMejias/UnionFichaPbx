<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Usuario
        </h2>
    </x-slot>

    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border p-6 max-w-2xl mx-auto">
            <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-ven-green focus:ring focus:ring-ven-green focus:ring-opacity-50">
                    @error('name')<span class="text-xs text-ven-red">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-ven-green focus:ring focus:ring-ven-green focus:ring-opacity-50">
                    @error('email')<span class="text-xs text-ven-red">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="role" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('role')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @error('password')<span class="text-xs text-red-500">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>
                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 font-bold uppercase tracking-widest text-xs rounded-md hover:bg-slate-300 transition">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold uppercase tracking-widest text-xs rounded-md hover:bg-blue-700 transition shadow-lg shadow-blue-200">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
