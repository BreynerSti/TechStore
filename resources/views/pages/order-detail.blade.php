@extends('layouts.app')

@section('title', 'Detalle del Pedido #' . $order->order_number . ' - TechStore')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($order->status === 'pending')
        <div class="mb-6 text-center">
            <a href="{{ route('orders.pay', $order->id) }}"
               class="inline-block bg-yellow-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors">
                Pagar ahora con PayPal
            </a>
        </div>
    @endif
    {{-- Header del pedido --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pedido #{{ $order->order_number }}</h1>
                <p class="text-gray-600 mt-2">Realizado el {{ $order->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
            </div>
            <a href="{{ route('my-orders') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Volver a Mis Pedidos
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Información del pedido --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Estado del pedido --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Estado del Pedido</h2>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                        'delivered' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200'
                    ];
                    $statusLabels = [
                        'pending' => 'Pendiente',
                        'processing' => 'En Proceso',
                        'shipped' => 'Enviado',
                        'delivered' => 'Entregado',
                        'cancelled' => 'Cancelado'
                    ];
                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    $label = $statusLabels[$order->status] ?? ucfirst($order->status);
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-full border {{ $color }}">
                    <span class="text-sm font-semibold">{{ $label }}</span>
                </div>
            </div>

            {{-- Productos del pedido --}}
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Productos ({{ $order->orderItems->count() }})</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <div class="p-6 flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($item->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <i data-lucide="image" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate">
                                    {{ $item->product->name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Cantidad: {{ $item->quantity }}
                                </p>
                                @if($item->product->category)
                                    <p class="text-xs text-gray-400">
                                        {{ $item->product->category->name }}
                                    </p>
                                @endif
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-semibold text-gray-900">
                                    ${{ number_format($item->price, 2) }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Total: ${{ number_format($item->price * $item->quantity, 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Resumen y detalles --}}
        <div class="space-y-6">
            {{-- Resumen del pedido --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del Pedido</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Impuestos:</span>
                            <span class="font-medium">${{ number_format($order->tax, 2) }}</span>
                        </div>
                    @endif
                    @if($order->shipping > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Envío:</span>
                            <span class="font-medium">${{ number_format($order->shipping, 2) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gray-200 pt-3">
                        <div class="flex justify-between text-base font-semibold">
                            <span class="text-gray-900">Total:</span>
                            <span class="text-tech-blue">${{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Información de envío --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de Envío</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $order->shipping_name }}</p>
                        <p class="text-sm text-gray-600">{{ $order->shipping_email }}</p>
                        @if($order->shipping_phone)
                            <p class="text-sm text-gray-600">{{ $order->shipping_phone }}</p>
                        @endif
                    </div>
                    <div class="border-t border-gray-200 pt-3">
                        <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Información de pago --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Información de Pago</h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Método de pago:</span>
                        <span class="font-medium">{{ $order->payment_method ? ucfirst($order->payment_method) : 'No especificado' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Estado del pago:</span>
                        <span class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $order->payment_status === 'paid' ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Notas del pedido --}}
            @if($order->notes)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Notas del Pedido</h2>
                    <p class="text-sm text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush 