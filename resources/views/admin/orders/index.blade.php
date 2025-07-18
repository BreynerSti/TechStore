@extends('layouts.admin')

@section('title', 'Pedidos - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Pedidos</h1>
    <div class="flex space-x-3">
        <button class="inline-flex items-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 transition">
            <i data-lucide="download" class="w-4 h-4 mr-2"></i>
            Exportar
        </button>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" method="GET" action="">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
            <input type="text" name="search" placeholder="ID o número de pedido... Ej: ORD-123456ABCDEF"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                <option value="">Todos los estados</option>
                <option value="pending">Pendiente</option>
                <option value="processing">Procesando</option>
                <option value="shipped">Enviado</option>
                <option value="delivered">Entregado</option>
                <option value="cancelled">Cancelado</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha desde</label>
            <input type="date" name="date_from" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha hasta</label>
            <input type="date" name="date_to" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>
        <div class="md:col-span-2 lg:col-span-4 flex justify-end">
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
                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Pedidos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pendientes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i data-lucide="truck" class="w-6 h-6 text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pagados</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['paid'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i data-lucide="dollar-sign" class="w-6 h-6 text-purple-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Cancelados</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['cancelled'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de pedidos -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 text-gray-900 font-semibold">#{{ $order->order_number ?? $order->id }}</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->user->name ?? $order->shipping_name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->email ?? $order->shipping_email }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-700">{{ $order->orderItems->count() ?? 0 }} productos</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($order->status === 'pending')
                            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">Pendiente</span>
                        @elseif($order->status === 'paid')
                            <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">Pagado</span>
                        @elseif($order->status === 'shipped')
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Enviado</span>
                        @elseif($order->status === 'delivered')
                            <span class="inline-block px-3 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-semibold">Entregado</span>
                        @elseif($order->status === 'cancelled')
                            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Cancelado</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">{{ ucfirst($order->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-tech-blue hover:underline text-sm font-semibold">Ver Detalles</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i data-lucide="package-x" class="w-12 h-12 text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">No hay pedidos</p>
                            <p class="text-sm">Aún no se han realizado pedidos en la tienda</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6 flex justify-center">
    {{ $orders->links() }}
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush
