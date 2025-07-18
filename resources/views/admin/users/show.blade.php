@extends('layouts.admin')

@section('title', 'Detalles del Usuario - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name ?? 'Usuario' }}</h1>
        <p class="text-gray-600 mt-1">Detalles del usuario #{{ $user->id ?? 'N/A' }}</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
        Volver a Usuarios
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Información principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Estado y rol del usuario -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Estado y Rol</h2>
                <div class="flex space-x-2">
                    @if($user->role === 'admin')
                        <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">Administrador</span>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm font-semibold">Usuario</span>
                    @endif
                </div>
            </div>
            
            <form action="{{ route('admin.users.update', $user->id ?? 1) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rol del usuario</label>
                        <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Usuario</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Notas del administrador</label>
                    <textarea id="admin_notes" name="admin_notes" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                              placeholder="Agregar notas sobre el usuario..."></textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-tech-blue text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        <!-- Información personal -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h2>
            <table class="w-full text-sm">
                <tr>
                    <td class="text-gray-600 py-2">Nombre completo:</td>
                    <td class="font-medium py-2">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="text-gray-600 py-2">Email:</td>
                    <td class="font-medium py-2">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="text-gray-600 py-2">Fecha de registro:</td>
                    <td class="font-medium py-2">{{ $user->created_at->format('d M, Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- Historial de pedidos -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Historial de Pedidos</h2>
            </div>
            <div class="p-6">
                @if($user->orders->count() > 0)
                    <div class="space-y-4">
                        @foreach($user->orders->take(5) as $order)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Pedido #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->orderItems->count() }} productos • ${{ number_format($order->total, 2) }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'paid' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            'shipped' => 'bg-blue-100 text-blue-700'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Pendiente',
                                            'paid' => 'Pagado',
                                            'cancelled' => 'Cancelado',
                                            'shipped' => 'Enviado'
                                        ];
                                    @endphp
                                    <span class="inline-block px-3 py-1 rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} text-xs font-semibold">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                    <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->format('d M, Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="shopping-bag" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-500">Este usuario no tiene pedidos aún</p>
                    </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">Total de pedidos: <span class="font-medium">{{ $user->orders->count() }}</span></p>
                            <p class="text-sm text-gray-600">Valor total: <span class="font-medium">${{ number_format($user->orders->sum('total'), 2) }}</span></p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}?user={{ $user->id }}" class="text-tech-blue hover:underline font-medium">
                            Ver todos los pedidos →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel lateral -->
    <div class="lg:col-span-1 space-y-6">
        {{-- Eliminado el resumen de usuario de la columna lateral --}}

        <!-- Acciones Rápidas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h2>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="role" value="admin">
                <button type="submit" class="w-full flex items-center justify-center px-4 py-3 bg-purple-500 text-white font-semibold rounded-lg shadow hover:bg-purple-600 transition mb-2">
                    <i data-lucide="shield" class="w-5 h-5 mr-2"></i>
                    Hacer Administrador
                </button>
            </form>
        </div>

        <!-- Información de seguridad -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Información de Seguridad</h2>
            </div>
            <div class="p-6">
                <div class="flex justify-between">
                    <span class="text-gray-600">Último login:</span>
                    <span class="font-medium">
                        {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush 