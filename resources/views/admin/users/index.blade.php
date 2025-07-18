@extends('layouts.admin')

@section('title', 'Usuarios - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
    <div class="flex space-x-3">
        <button class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition">
            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
            Exportar
        </button>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="GET" action="">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <input type="text" name="search" placeholder="Nombre, email..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="customer">Usuario</option>
            </select>
        </div>
        <div class="md:col-span-3 flex justify-end">
            <button type="submit" class="bg-tech-blue text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                <i data-lucide="search" class="w-4 h-4 mr-2 inline"></i>
                Filtrar
            </button>
        </div>
    </form>
</div>

<!-- Estadísticas rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i data-lucide="user-check" class="w-6 h-6 text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Clientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['customers']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i data-lucide="shield" class="w-6 h-6 text-purple-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Administradores</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['admins']) }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i data-lucide="calendar" class="w-6 h-6 text-orange-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Nuevos este mes</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($newUsersThisMonth ?? 0) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rol</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pedidos</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registrado</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @if($users->count() > 0)
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 {{ $user->role === 'admin' ? 'bg-purple-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center mr-3">
                                    <i data-lucide="{{ $user->role === 'admin' ? 'shield' : 'user' }}" class="w-5 h-5 {{ $user->role === 'admin' ? 'text-purple-600' : 'text-blue-600' }}"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-500">ID: #{{ $user->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">Administrador</span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">Usuario</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Activo</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-700">{{ $user->orders_count ?? 0 }} pedidos</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-tech-blue hover:underline text-sm font-semibold">Ver</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center">
                        <div class="text-gray-500">
                            <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 text-gray-400"></i>
                            <p>No hay usuarios registrados</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6 flex justify-center">
    {{ $users->links() }}
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush 