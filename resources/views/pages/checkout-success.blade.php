@extends('layouts.app')

@section('title', 'Pedido Completado - TechStore')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Pedido Completado!</h1>
        <p class="text-gray-600">Gracias por tu compra. Tu pedido ha sido procesado exitosamente.</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Detalles del Pedido</h2>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                #{{ $order->order_number }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Información del cliente -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Información de Envío</h3>
                <div class="space-y-2 text-gray-600">
                    <p><strong>Nombre:</strong> {{ $order->shipping_name }}</p>
                    <p><strong>Email:</strong> {{ $order->shipping_email }}</p>
                    @if($order->shipping_phone)
                        <p><strong>Teléfono:</strong> {{ $order->shipping_phone }}</p>
                    @endif
                    <p><strong>Dirección:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Ciudad:</strong> {{ $order->shipping_city }}</p>
                    <p><strong>Código Postal:</strong> {{ $order->shipping_postal_code }}</p>
                </div>
            </div>

            <!-- Resumen del pedido -->
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">Resumen del Pedido</h3>
                <div class="space-y-2 text-gray-600">
                    <p><strong>Estado:</strong> 
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Total:</strong> <span class="font-semibold text-tech-blue">${{ number_format($order->total, 2) }}</span></p>
                </div>
            </div>
        </div>

        @if($order->notes)
            <div class="mt-6 pt-6 border-t">
                <h3 class="font-semibold text-gray-900 mb-2">Notas del Pedido</h3>
                <p class="text-gray-600">{{ $order->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Información de Pago -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-semibold mb-6">Información de Pago</h2>
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

    <!-- Productos del pedido -->
    <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
        <h2 class="text-xl font-semibold mb-6">Productos del Pedido</h2>
        <div class="space-y-4">
            @foreach($order->orderItems as $item)
            <div class="flex items-center justify-between py-4 border-b border-gray-200 last:border-b-0">
                <div class="flex items-center space-x-4">
                    <img src="{{ $item->product->image ?? '/images/placeholder.jpg' }}" 
                         alt="{{ $item->product->name }}" 
                         class="w-16 h-16 object-cover rounded">
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $item->product->name }}</h3>
                        <p class="text-gray-500 text-sm">Cantidad: {{ $item->quantity }}</p>
                        <p class="text-gray-500 text-sm">Precio unitario: ${{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-tech-blue">${{ number_format($item->total, 2) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 pt-6 border-t">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total del Pedido:</span>
                <span class="text-2xl font-bold text-tech-blue">${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Acciones -->
    <div class="text-center space-y-4">
        @if($order->status === 'pending')
            <a href="{{ route('orders.pay', $order->id) }}"
               class="inline-block bg-yellow-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-yellow-600 transition-colors mb-2">
                Pagar ahora con PayPal
            </a>
        @endif
        <a href="{{ route('home') }}" 
           class="inline-block bg-tech-blue text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
            Continuar Comprando
        </a>
        
        <div class="text-gray-600">
            <p>Te hemos enviado un email de confirmación a <strong>{{ $order->shipping_email }}</strong></p>
            <p class="text-sm mt-2">Si tienes alguna pregunta, no dudes en contactarnos.</p>
        </div>
    </div>
</div>
@endsection 