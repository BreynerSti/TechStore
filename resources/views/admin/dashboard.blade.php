@extends('layouts.admin')

@section('title', 'Dashboard - Panel de Administración')

@section('content')
<div class="space-y-6">
    <!-- Título y estadísticas generales -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Bienvenido al panel de administración de TechStore</p>
    </div>

            <!-- Tarjetas de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total de productos -->
            <div class="bg-white rounded-lg shadow p-6">
                <a href="{{ route('admin.products.index') }}" class="block hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i data-lucide="package" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Productos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_products']) }}</p>
                            </div>
                        </div>
                        <div class="text-blue-600">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total de pedidos -->
            <div class="bg-white rounded-lg shadow p-6">
                <a href="{{ route('admin.orders.index') }}" class="block hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Pedidos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_orders']) }}</p>
                            </div>
                        </div>
                        <div class="text-green-600">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Usuarios registrados -->
            <div class="bg-white rounded-lg shadow p-6">
                <a href="{{ route('admin.users.index') }}" class="block hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i data-lucide="users" class="w-6 h-6"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Usuarios</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                            </div>
                        </div>
                        <div class="text-purple-600">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Ingresos del mes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Ingresos del Mes</p>
                        <p class="text-2xl font-semibold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

    <!-- Gráficos y contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Últimos pedidos -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Últimos Pedidos</h3>
            </div>
            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                        <i data-lucide="user" class="w-5 h-5 text-gray-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Pedido #{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->user ? $order->user->name : 'Cliente' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</p>
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
                                    <span class="inline-block px-2 py-1 text-xs {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} rounded-full">
                                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="shopping-bag" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-500">No hay pedidos recientes</p>
                    </div>
                @endif
                <div class="mt-6">
                    <a href="{{ route('admin.orders.index') }}" class="text-tech-blue hover:text-blue-700 font-medium">
                        Ver todos los pedidos →
                    </a>
                </div>
            </div>
        </div>

        <!-- Productos más vendidos -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Productos Más Vendidos</h3>
            </div>
            <div class="p-6">
                @if($topProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProducts as $product)
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center overflow-hidden">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $product->total_sold ?? 0 }} ventas</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">${{ number_format($product->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="package" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-500">No hay productos vendidos aún</p>
                    </div>
                @endif
                <div class="mt-6">
                    <a href="{{ route('admin.products.index') }}" class="text-tech-blue hover:text-blue-700 font-medium">
                        Ver todos los productos →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas de pedidos por estado -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estados de Pedidos</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $orderStats['pending'] }}</div>
                <div class="text-sm text-yellow-700">Pendientes</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $orderStats['paid'] }}</div>
                <div class="text-sm text-green-700">Pagados</div>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $orderStats['shipped'] }}</div>
                <div class="text-sm text-blue-700">Enviados</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600">{{ $orderStats['cancelled'] }}</div>
                <div class="text-sm text-red-700">Cancelados</div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.products.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="plus" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">Agregar Producto</p>
                    <p class="text-sm text-gray-500">Crear un nuevo producto</p>
                </div>
            </a>

            <a href="{{ route('admin.categories.create') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i data-lucide="folder-plus" class="w-5 h-5 text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">Nueva Categoría</p>
                    <p class="text-sm text-gray-500">Crear una categoría</p>
                </div>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i data-lucide="eye" class="w-5 h-5 text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium text-gray-900">Ver Pedidos</p>
                    <p class="text-sm text-gray-500">Gestionar pedidos</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scripts específicos para el dashboard
    console.log('Dashboard cargado');
</script>
@endpush
