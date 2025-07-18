@extends('layouts.app')

@section('title', 'TechStore - Inicio')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-tech-blue to-purple-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Tecnología de Vanguardia
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-blue-100">
                    Descubre los mejores productos tecnológicos con garantía y calidad
                </p>
                <a href="{{ route('products') }}" class="bg-white text-tech-blue px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-block">
                    Ver Productos
                </a>
            </div>
        </div>
    </section>

    <!-- Categorías Destacadas -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">
                Categorías Destacadas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($categories as $category)
                    <a href="{{ route('products', ['category' => $category->id]) }}" 
                       class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 block">
                        <div class="w-16 h-16 bg-tech-blue rounded-full flex items-center justify-center mx-auto mb-4">
                            @php
                                $icon = 'package';
                                if (stripos($category->name, 'smartphone') !== false || stripos($category->name, 'celular') !== false) {
                                    $icon = 'smartphone';
                                } elseif (stripos($category->name, 'laptop') !== false || stripos($category->name, 'computadora') !== false) {
                                    $icon = 'laptop';
                                } elseif (stripos($category->name, 'accesorio') !== false || stripos($category->name, 'headphone') !== false) {
                                    $icon = 'headphones';
                                } elseif (stripos($category->name, 'tablet') !== false) {
                                    $icon = 'tablet';
                                } elseif (stripos($category->name, 'cámara') !== false || stripos($category->name, 'camera') !== false) {
                                    $icon = 'camera';
                                }
                            @endphp
                            <i data-lucide="{{ $icon }}" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ $category->name }}</h3>
                        <p class="text-gray-600">{{ $category->description ?? 'Productos de calidad' }}</p>
                        <div class="mt-4 text-tech-blue font-medium">
                            Ver productos →
                        </div>
                    </a>
                @empty
                    <!-- Categorías por defecto si no hay datos -->
                    <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-tech-blue rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="smartphone" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Smartphones</h3>
                        <p class="text-gray-600">Los últimos modelos con tecnología avanzada</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="laptop" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Laptops</h3>
                        <p class="text-gray-600">Computadoras portátiles de alto rendimiento</p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-6 text-center hover:shadow-lg transition-shadow">
                        <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="headphones" class="w-8 h-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Accesorios</h3>
                        <p class="text-gray-600">Todo lo que necesitas para tu tecnología</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Productos Destacados -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900">
                Productos Destacados
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <a href="{{ route('products.show', $product->id) }}" class="block">
                            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <a href="{{ route('products.show', $product->id) }}" class="block">
                                <h3 class="font-semibold text-gray-900 mb-2 hover:text-tech-blue transition-colors">{{ $product->name }}</h3>
                            </a>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 60) }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-tech-blue font-bold text-lg">${{ number_format($product->price, 2) }}</span>
                                <button onclick="addToCart({{ $product->id }})" 
                                        class="bg-tech-blue text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Producto de ejemplo si no hay datos -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Producto Ejemplo</h3>
                            <p class="text-gray-600 text-sm mb-3">Descripción del producto</p>
                            <div class="flex justify-between items-center">
                                <span class="text-tech-blue font-bold">$299.99</span>
                                <button class="bg-tech-blue text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                    Agregar
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
            @if($featuredProducts->count() > 0)
                <div class="text-center mt-8">
                    <a href="{{ route('products') }}" class="bg-tech-blue text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block">
                        Ver Todos los Productos
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Función para agregar productos al carrito
    function addToCart(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                showNotification('Producto agregado al carrito', 'success');
                // Actualizar contador del carrito si existe
                updateCartCount();
            } else {
                showNotification(data.message || 'Error al agregar al carrito', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al agregar al carrito', 'error');
        });
    }

    // Función para mostrar notificaciones
    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        if (type === 'success') {
            notification.className += ' bg-green-500 text-white';
        } else if (type === 'error') {
            notification.className += ' bg-red-500 text-white';
        } else {
            notification.className += ' bg-blue-500 text-white';
        }
        
        notification.textContent = message;
        document.body.appendChild(notification);
        
        // Animar entrada
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Función para actualizar contador del carrito
    function updateCartCount() {
        fetch('/cart/items')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                const count = data.items.length;
                cartCount.textContent = count;
                cartCount.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error updating cart count:', error);
        });
    }

    // Inicializar cuando la página cargue
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página de inicio cargada');
        // Actualizar contador del carrito al cargar la página
        updateCartCount();
    });
</script>
@endpush
