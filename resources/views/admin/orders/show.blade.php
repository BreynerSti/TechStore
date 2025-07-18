@extends('layouts.admin')

@section('title', 'Detalles del Pedido - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pedido #{{ $order->order_number ?? $order->id }}</h1>
        <p class="text-gray-600 mt-1">Detalles completos del pedido</p>
    </div>
    <a href="{{ route('admin.orders.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
        Volver a Pedidos
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Información principal -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Estado del pedido -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Estado del Pedido</h2>
                <span class="inline-block px-3 py-1 rounded-full 
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-700
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-700
                    @elseif($order->status === 'shipped') bg-green-100 text-green-700
                    @elseif($order->status === 'delivered') bg-purple-100 text-purple-700
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-700
                    @else bg-gray-100 text-gray-700 @endif
                    text-sm font-semibold">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="flex items-center space-x-4">
                    <label for="status" class="text-sm font-medium text-gray-700">Cambiar estado:</label>
                    <select id="status" name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                        <option value="pending" @if($order->status=='pending') selected @endif>Pendiente</option>
                        <option value="processing" @if($order->status=='processing') selected @endif>Procesando</option>
                        <option value="shipped" @if($order->status=='shipped') selected @endif>Enviado</option>
                        <option value="delivered" @if($order->status=='delivered') selected @endif>Entregado</option>
                        <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelado</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-tech-blue text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        Actualizar
                    </button>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notas del administrador</label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                              placeholder="Agregar notas sobre el pedido...">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </form>
        </div>

        <!-- Productos del pedido -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Productos del Pedido</h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($order->orderItems as $item)
                        <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                @if($item->product && $item->product->main_image)
                                    <img src="{{ Storage::url($item->product->main_image) }}" alt="{{ $item->product->name }}" class="w-14 h-14 object-cover rounded-lg">
                                @else
                                    <i data-lucide="package" class="w-8 h-8 text-gray-600"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-medium text-gray-900">{{ $item->product->name ?? 'Producto eliminado' }}</h3>
                                <p class="text-sm text-gray-500">SKU: {{ $item->product->sku ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">${{ number_format($item->price, 2) }}</p>
                                <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500">No hay productos en este pedido.</div>
                    @endforelse
                </div>
                <!-- Resumen de costos -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Envío:</span>
                            <span class="font-medium">${{ number_format($order->shipping_cost, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Impuestos:</span>
                            <span class="font-medium">${{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold border-t pt-2">
                            <span>Total:</span>
                            <span>${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información del cliente -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Información del Cliente</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-3">Datos Personales</h3>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">Nombre:</span> {{ $order->user->name ?? $order->shipping_name }}</p>
                            <p><span class="font-medium">Email:</span> {{ $order->user->email ?? $order->shipping_email }}</p>
                            <p><span class="font-medium">Teléfono:</span> {{ $order->user->phone ?? $order->shipping_phone }}</p>
                            <p><span class="font-medium">Cliente desde:</span> {{ $order->user ? $order->user->created_at->format('M Y') : '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-3">Dirección de Envío</h3>
                        <div class="space-y-2 text-sm">
                            <p>{{ $order->shipping_name }}</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p>{{ $order->shipping_city }}</p>
                            <p>{{ $order->shipping_postal_code }}</p>
                            <p>{{ $order->shipping_country }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de pago -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Información de Pago</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-medium text-gray-900 mb-3">Método de Pago</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="credit-card" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium">{{ ucfirst($order->payment_method ?? 'Desconocido') }}</p>
                                <p class="text-sm text-gray-500">{{ $order->payment_details ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-900 mb-3">Estado del Pago</h3>
                        <div class="flex items-center space-x-2">
                            @if($order->payment_status === 'paid')
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-green-700">Pagado</span>
                            @else
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <span class="text-sm font-medium text-yellow-700">Pendiente</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($order->paid_at)
                                Pagado el {{ $order->paid_at->format('d \d\e M, Y') }}
                            @else
                                No pagado aún
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel lateral -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Resumen del pedido -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Fecha:</span>
                    <span class="font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Estado:</span>
                    <span class="font-medium">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total:</span>
                    <span class="font-medium">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Historial de estados -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de Estados</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm font-medium">Pendiente</p>
                        <p class="text-xs text-gray-500">20 Enero, 2024 - 14:30</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-gray-300 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm text-gray-500">Procesando</p>
                        <p class="text-xs text-gray-400">Pendiente</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-gray-300 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm text-gray-500">Enviado</p>
                        <p class="text-xs text-gray-400">Pendiente</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-gray-300 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm text-gray-500">Entregado</p>
                        <p class="text-xs text-gray-400">Pendiente</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="space-y-3">
                <button class="w-full flex items-center justify-center px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                    <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                    Enviar Email al Cliente
                </button>
                <button class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                    Imprimir Factura
                </button>
                <button class="w-full flex items-center justify-center px-4 py-2 bg-purple-500 text-white font-semibold rounded-lg hover:bg-purple-600 transition">
                    <i data-lucide="truck" class="w-4 h-4 mr-2"></i>
                    Generar Guía de Envío
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush 