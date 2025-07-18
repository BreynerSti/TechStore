@extends('layouts.app')

@section('title', 'Mi Perfil - TechStore')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8">Mi Perfil</h1>
    @if(auth()->user()->role === 'admin')
        <a href="{{ url('/admin') }}" class="inline-block mb-6 px-5 py-2 bg-yellow-500 text-white font-semibold rounded hover:bg-yellow-600 transition-colors">
            Ir al Panel de Admin
        </a>
    @endif
    <div class="bg-white rounded-lg shadow p-8 mb-10">
        <div class="flex items-center space-x-6 mb-6">
            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                <i data-lucide="user" class="w-10 h-10 text-gray-400"></i>
            </div>
            <div>
                <div class="text-xl font-semibold text-gray-900">{{ auth()->user()->name ?? 'Usuario Demo' }}</div>
                <div class="text-gray-500">{{ auth()->user()->email ?? 'usuario@demo.com' }}</div>
            </div>
        </div>
        <div class="flex flex-col md:flex-row gap-4">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors w-full md:w-auto">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    
    {{-- Resumen de pedidos recientes --}}
    <div class="bg-white rounded-lg shadow p-8 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Pedidos Recientes</h2>
            <a href="{{ route('my-orders') }}" class="text-tech-blue hover:text-blue-700 font-semibold text-sm">
                Ver Todos los Pedidos →
            </a>
        </div>
        
        @php
            $recentOrders = auth()->check() 
                ? auth()->user()->orders()->with('orderItems')->orderBy('created_at', 'desc')->take(3)->get()
                : \App\Models\Order::with('orderItems')->orderBy('created_at', 'desc')->take(3)->get();
        @endphp
        
        @if($recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-tech-blue rounded-lg flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">#{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-tech-blue">${{ number_format($order->total, 2) }}</p>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800'
                                ];
                                $statusLabels = [
                                    'pending' => 'Pendiente',
                                    'processing' => 'En Proceso',
                                    'shipped' => 'Enviado',
                                    'delivered' => 'Entregado',
                                    'cancelled' => 'Cancelado'
                                ];
                                $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                $label = $statusLabels[$order->status] ?? ucfirst($order->status);
                            @endphp
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $color }}">
                                {{ $label }}
                            </span>
                        </div>
                        <a href="{{ route('orders.show', $order) }}" class="text-tech-blue hover:text-blue-700 font-semibold text-sm">
                            Ver Detalle
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                </div>
                <p class="text-gray-600 mb-4">No tienes pedidos aún</p>
                <a href="{{ route('products') }}" class="inline-flex items-center px-4 py-2 bg-tech-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="shopping-bag" class="w-4 h-4 mr-2"></i>
                    Hacer mi Primera Compra
                </a>
            </div>
        @endif
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
