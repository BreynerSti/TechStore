@extends('layouts.app')

@section('title', 'Checkout - TechStore')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8">Finalizar Compra</h1>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Formulario de datos de envío -->
        <div class="bg-white rounded-lg shadow p-8">
            <h2 class="text-xl font-semibold mb-6">Datos de Envío</h2>
            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" class="mt-1 w-full px-4 py-2 border rounded-lg @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="mt-1 w-full px-4 py-2 border rounded-lg @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="address" name="address" value="{{ old('address') }}" class="mt-1 w-full px-4 py-2 border rounded-lg @error('address') border-red-500 @enderror" required>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" class="mt-1 w-full px-4 py-2 border rounded-lg @error('city') border-red-500 @enderror" required>
                        @error('city')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" class="mt-1 w-full px-4 py-2 border rounded-lg @error('postal_code') border-red-500 @enderror" required>
                        @error('postal_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notas adicionales (opcional)</label>
                    <textarea id="notes" name="notes" rows="2" class="mt-1 w-full px-4 py-2 border rounded-lg @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>
        
        <!-- Resumen del pedido -->
        <div class="bg-gray-50 rounded-lg shadow p-8">
            <h2 class="text-xl font-semibold mb-6">Resumen del Pedido</h2>
            <div class="divide-y divide-gray-200 mb-6">
                @foreach($cart as $item)
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center space-x-4">
                        @if(!empty($item['image']))
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-12 h-12 object-cover rounded">
                        @else
                            <img src="/images/placeholder.jpg" alt="Sin imagen" class="w-12 h-12 object-cover rounded">
                        @endif
                        <div>
                            <div class="font-semibold text-gray-900">{{ $item['name'] }}</div>
                            <div class="text-gray-500 text-sm">Cantidad: {{ $item['quantity'] }}</div>
                        </div>
                    </div>
                    <div class="text-tech-blue font-bold">${{ number_format($item['price'] * $item['quantity'], 2) }}</div>
                </div>
                @endforeach
            </div>
            
            <div class="border-t pt-4">
                <div class="flex justify-between mb-4">
                    <span class="font-semibold text-gray-700">Subtotal:</span>
                    <span class="font-semibold text-gray-900">${{ number_format($total, 2) }}</span>
                </div>
                <div class="flex justify-between mb-4">
                    <span class="font-semibold text-gray-700">Envío:</span>
                    <span class="font-semibold text-gray-900">
                        @if($shipping == 0)
                            Gratis
                        @else
                            ${{ number_format($shipping, 2) }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between mb-6">
                    <span class="text-lg font-bold text-gray-900">Total:</span>
                    <span class="text-2xl font-bold text-tech-blue">${{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
            
            <button type="submit" form="checkout-form" class="w-full bg-tech-blue text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-lg">
                Finalizar Compra
            </button>
            
            <div class="mt-4 text-center">
                <a href="{{ route('cart') }}" class="text-tech-blue hover:underline">← Volver al carrito</a>
            </div>
        </div>
    </div>
</div>

<!-- Formulario oculto para el botón -->
<form id="checkout-form" action="{{ route('checkout.store') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="name" id="form-name">
    <input type="hidden" name="email" id="form-email">
    <input type="hidden" name="phone" id="form-phone">
    <input type="hidden" name="address" id="form-address">
    <input type="hidden" name="city" id="form-city">
    <input type="hidden" name="postal_code" id="form-postal_code">
    <input type="hidden" name="notes" id="form-notes">
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const inputs = form.querySelectorAll('input[type="hidden"]');
    
    // Sincronizar los campos del formulario visible con el formulario oculto
    inputs.forEach(input => {
        const visibleInput = document.getElementById(input.name);
        if (visibleInput) {
            visibleInput.addEventListener('input', function() {
                input.value = this.value;
            });
        }
    });
    
    // Validar formulario antes de enviar
    form.addEventListener('submit', function(e) {
        const requiredFields = ['name', 'email', 'address', 'city', 'postal_code'];
        let isValid = true;
        
        requiredFields.forEach(field => {
            const input = document.getElementById(field);
            if (!input.value.trim()) {
                input.classList.add('border-red-500');
                isValid = false;
            } else {
                input.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, completa todos los campos requeridos.');
        }
    });
});
</script>
@endpush
