@extends('layouts.app')

@section('title', $product->name . ' - TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-tech-blue">
                    <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                    <a href="{{ route('products') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-tech-blue md:ml-2">
                        Productos
                    </a>
                </div>
            </li>
            @if($product->category)
            <li>
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                    <a href="{{ route('products', ['category' => $product->category->id]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-tech-blue md:ml-2">
                        {{ $product->category->name }}
                    </a>
                </div>
            </li>
            @endif
            <li aria-current="page">
                <div class="flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Galería de imágenes -->
        <div class="space-y-4">
            <!-- Imagen principal -->
            <div class="relative">
                <div id="main-image-container" class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    @if($product->images->count() > 0)
                        <img id="main-image" 
                             src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover cursor-zoom-in transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i data-lucide="image" class="w-24 h-24 text-gray-400"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Zoom overlay -->
                <div id="zoom-overlay" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center">
                    <div class="relative max-w-4xl max-h-full p-4">
                        <button id="close-zoom" class="absolute top-4 right-4 text-white hover:text-gray-300">
                            <i data-lucide="x" class="w-6 h-6"></i>
                        </button>
                        <img id="zoom-image" src="" alt="" class="max-w-full max-h-full object-contain">
                    </div>
                </div>
            </div>

            <!-- Miniaturas -->
            @if($product->images->count() > 1)
            <div class="flex space-x-2 overflow-x-auto pb-2">
                @foreach($product->images as $index => $image)
                    <button onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')" 
                            class="thumbnail-btn flex-shrink-0 w-20 h-20 border-2 border-gray-200 rounded-lg overflow-hidden hover:border-tech-blue transition-colors {{ $index === 0 ? 'border-tech-blue' : '' }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="" 
                             class="w-full h-full object-cover">
                    </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Información del producto -->
        <div class="space-y-6">
            <!-- Categoría -->
            @if($product->category)
            <div class="flex items-center space-x-2">
                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                    {{ $product->category->name }}
                </span>
                @if($product->is_featured)
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                    Destacado
                </span>
                @endif
            </div>
            @endif

            <!-- Nombre del producto -->
            <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>

            <!-- Precio -->
            <div class="flex items-baseline space-x-2">
                <span class="text-3xl font-bold text-tech-blue">${{ number_format($product->price, 2) }}</span>
                @if($product->sku)
                <span class="text-sm text-gray-500">SKU: {{ $product->sku }}</span>
                @endif
            </div>

            <!-- Estado del stock -->
            <div class="flex items-center space-x-2">
                @if($product->stock > 0)
                    @if($product->stock < 10)
                        <div class="flex items-center text-yellow-600">
                            <i data-lucide="alert-triangle" class="w-4 h-4 mr-1"></i>
                            <span class="text-sm font-medium">Solo quedan {{ $product->stock }} unidades</span>
                        </div>
                    @else
                        <div class="flex items-center text-green-600">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                            <span class="text-sm font-medium">En stock ({{ $product->stock }} disponibles)</span>
                        </div>
                    @endif
                @else
                    <div class="flex items-center text-red-600">
                        <i data-lucide="x-circle" class="w-4 h-4 mr-1"></i>
                        <span class="text-sm font-medium">Agotado</span>
                    </div>
                @endif
            </div>

            <!-- Descripción -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h3>
                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
            </div>

            <!-- Formulario de compra -->
            @if($product->stock > 0)
            <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Agregar al carrito</h3>
                
                <!-- Selector de cantidad -->
                <div class="flex items-center space-x-4">
                    <label for="quantity" class="text-sm font-medium text-gray-700">Cantidad:</label>
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button type="button" onclick="changeQuantity(-1)" 
                                class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                            <i data-lucide="minus" class="w-4 h-4"></i>
                        </button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" 
                               class="w-16 text-center border-0 focus:ring-0 bg-transparent">
                        <button type="button" onclick="changeQuantity(1)" 
                                class="px-3 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 transition-colors">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <span class="text-sm text-gray-500">Máximo: {{ $product->stock }}</span>
                </div>

                <!-- Botón agregar al carrito -->
                <button onclick="addToCart({{ $product->id }})" 
                        class="w-full bg-tech-blue text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>Agregar al carrito</span>
                </button>

                <!-- Acciones adicionales -->
                <div class="flex space-x-2">
                    <button onclick="addToWishlist({{ $product->id }})" 
                            class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center justify-center space-x-2">
                        <i data-lucide="heart" class="w-4 h-4"></i>
                        <span>Favoritos</span>
                    </button>
                    <button onclick="shareProduct()" 
                            class="flex-1 bg-gray-100 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center justify-center space-x-2">
                        <i data-lucide="share-2" class="w-4 h-4"></i>
                        <span>Compartir</span>
                    </button>
                </div>
            </div>
            @else
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                    <span class="text-red-800 font-medium">Producto agotado</span>
                </div>
                <p class="text-red-700 text-sm">Este producto no está disponible actualmente. Te notificaremos cuando vuelva a estar en stock.</p>
                <button onclick="notifyWhenAvailable({{ $product->id }})" 
                        class="mt-4 bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition-colors">
                    Notificar cuando esté disponible
                </button>
            </div>
            @endif

            <!-- Información adicional -->
            <div class="border-t border-gray-200 pt-6 space-y-4">
                <div class="flex items-center space-x-2">
                    <i data-lucide="truck" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-sm text-gray-600">Envío gratuito en compras superiores a $50</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i data-lucide="shield-check" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-sm text-gray-600">Garantía de 1 año</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i data-lucide="refresh-cw" class="w-5 h-5 text-gray-400"></i>
                    <span class="text-sm text-gray-600">Devolución gratuita en 30 días</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos relacionados -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-16">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Productos relacionados</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedProducts as $relatedProduct)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                <a href="{{ route('products.show', $relatedProduct->id) }}" class="block">
                    <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if($relatedProduct->images->count() > 0)
                            <img src="{{ asset('storage/' . $relatedProduct->images->first()->image_path) }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        @else
                            <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <a href="{{ route('products.show', $relatedProduct->id) }}" class="block">
                        <h3 class="font-semibold text-gray-900 mb-2 hover:text-tech-blue transition-colors">{{ $relatedProduct->name }}</h3>
                    </a>
                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($relatedProduct->description, 60) }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-tech-blue font-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                        @if($relatedProduct->stock > 0)
                            <button onclick="addToCart({{ $relatedProduct->id }})" 
                                    class="bg-tech-blue text-white px-3 py-1 rounded text-sm hover:bg-blue-700 transition-colors">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            </button>
                        @else
                            <span class="text-red-500 text-sm">Agotado</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Variables globales
    let currentQuantity = 1;
    const maxQuantity = {{ $product->stock }};

    // Función para cambiar imagen principal
    function changeMainImage(imageSrc) {
        const mainImage = document.getElementById('main-image');
        const zoomImage = document.getElementById('zoom-image');
        
        if (mainImage) {
            mainImage.src = imageSrc;
        }
        if (zoomImage) {
            zoomImage.src = imageSrc;
        }

        // Actualizar estado activo de miniaturas
        document.querySelectorAll('.thumbnail-btn').forEach(btn => {
            btn.classList.remove('border-tech-blue');
            btn.classList.add('border-gray-200');
        });
        event.target.closest('.thumbnail-btn').classList.remove('border-gray-200');
        event.target.closest('.thumbnail-btn').classList.add('border-tech-blue');
    }

    // Función para cambiar cantidad
    function changeQuantity(delta) {
        const quantityInput = document.getElementById('quantity');
        let newQuantity = currentQuantity + delta;
        
        if (newQuantity >= 1 && newQuantity <= maxQuantity) {
            currentQuantity = newQuantity;
            quantityInput.value = currentQuantity;
        }
    }

    // Función para agregar al carrito
    function addToCart(productId) {
        const quantity = document.getElementById('quantity').value;
        
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: parseInt(quantity)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`Producto agregado al carrito (${quantity} unidades)`, 'success');
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
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Función para agregar a favoritos
    function addToWishlist(productId) {
        showNotification('Función de favoritos próximamente', 'info');
    }

    // Función para compartir producto
    function shareProduct() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $product->name }}',
                text: '{{ $product->description }}',
                url: window.location.href
            });
        } else {
            // Fallback: copiar URL al portapapeles
            navigator.clipboard.writeText(window.location.href);
            showNotification('Enlace copiado al portapapeles', 'success');
        }
    }

    // Función para notificar cuando esté disponible
    function notifyWhenAvailable(productId) {
        showNotification('Te notificaremos cuando el producto esté disponible', 'success');
    }

    // Zoom de imagen
    document.addEventListener('DOMContentLoaded', function() {
        const mainImage = document.getElementById('main-image');
        const zoomOverlay = document.getElementById('zoom-overlay');
        const zoomImage = document.getElementById('zoom-image');
        const closeZoom = document.getElementById('close-zoom');

        if (mainImage && zoomOverlay && zoomImage) {
            mainImage.addEventListener('click', function() {
                zoomImage.src = this.src;
                zoomOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            closeZoom.addEventListener('click', function() {
                zoomOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });

            zoomOverlay.addEventListener('click', function(e) {
                if (e.target === zoomOverlay) {
                    zoomOverlay.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });
        }

        // Validar cantidad al cambiar input
        const quantityInput = document.getElementById('quantity');
        if (quantityInput) {
            quantityInput.addEventListener('change', function() {
                let value = parseInt(this.value);
                if (value < 1) value = 1;
                if (value > maxQuantity) value = maxQuantity;
                currentQuantity = value;
                this.value = value;
            });
        }
    });
</script>
@endpush
