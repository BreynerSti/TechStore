@extends('layouts.app')

@section('title', 'TechStore - Productos')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header de la página -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Catálogo de Productos</h1>
            <p class="text-gray-600">Encuentra los mejores productos tecnológicos</p>
        </div>

        <!-- Filtros superiores -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form action="{{ route('products') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Búsqueda -->
                    <div class="md:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Buscar productos por nombre, descripción..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                    </div>

                    <!-- Categoría -->
                    <div>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                            <option value="">Todas las categorías</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ordenamiento -->
                    <div>
                        <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                            <option value="">Ordenar por</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nombre A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nombre Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Más Recientes</option>
                        </select>
                    </div>
                </div>

                <!-- Filtros adicionales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Rango de precio -->
                    <div class="flex space-x-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               placeholder="Precio mínimo" min="0"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                        <span class="flex items-center text-gray-500">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               placeholder="Precio máximo" min="0"
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                    </div>

                    <!-- Solo en stock -->
                    <div class="flex items-center">
                        <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }}
                               class="w-4 h-4 text-tech-blue border-gray-300 rounded focus:ring-tech-blue">
                        <label class="ml-2 text-sm text-gray-700">Solo productos en stock</label>
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-tech-blue text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            <i data-lucide="search" class="w-4 h-4 inline mr-2"></i>
                            Filtrar
                        </button>
                        <a href="{{ route('products') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                            Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Contenido principal -->
        <div class="flex flex-col gap-8">
            <!-- Lista de productos -->
            <div class="flex-1">
                <!-- Header de resultados -->
                <div class="flex justify-between items-center mb-6">
                    <div class="text-sm text-gray-600">
                        Mostrando {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} 
                        de {{ $products->total() }} productos
                    </div>
                    
                    <!-- Controles de vista -->
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <button id="grid-view" class="p-2 rounded-lg bg-tech-blue text-white">
                                <i data-lucide="grid" class="w-4 h-4"></i>
                            </button>
                            <button id="list-view" class="p-2 rounded-lg bg-gray-200 text-gray-600 hover:bg-gray-300">
                                <i data-lucide="list" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid de productos -->
                <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Imagen del producto -->
                            <a href="{{ route('products.show', $product->id) }}" class="block">
                                <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden relative">
                                    @if($product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    @else
                                        <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                                    @endif
                                    
                                    <!-- Badge de stock -->
                                    @if($product->stock <= 0)
                                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                            Agotado
                                        </div>
                                    @elseif($product->stock < 10)
                                        <div class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                                            Stock bajo
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <!-- Información del producto -->
                            <div class="p-4">
                                <a href="{{ route('products.show', $product->id) }}" class="block">
                                    <h3 class="font-semibold text-gray-900 mb-2 hover:text-tech-blue transition-colors line-clamp-2">
                                        {{ $product->name }}
                                    </h3>
                                </a>
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                                
                                <!-- Categoría -->
                                @if($product->category)
                                    <div class="text-xs text-gray-500 mb-3">
                                        <i data-lucide="tag" class="w-3 h-3 inline mr-1"></i>
                                        {{ $product->category->name }}
                                    </div>
                                @endif

                                <!-- Precio y acciones -->
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-tech-blue font-bold text-lg">${{ number_format($product->price, 2) }}</span>
                                        @if($product->stock > 0)
                                            <div class="text-xs text-gray-500">Stock: {{ $product->stock }}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('products.show', $product->id) }}" 
                                           class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        @if($product->stock > 0)
                                            <button onclick="addToCart({{ $product->id }})" 
                                                    class="bg-tech-blue text-white px-3 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                            </button>
                                        @else
                                            <button disabled 
                                                    class="bg-gray-300 text-gray-500 px-3 py-2 rounded-lg text-sm cursor-not-allowed">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <i data-lucide="package" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron productos</h3>
                            <p class="text-gray-600 mb-4">Intenta ajustar tus filtros de búsqueda</p>
                            <a href="{{ route('products') }}" class="bg-tech-blue text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Ver todos los productos
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                @if($products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

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
                showNotification('Producto agregado al carrito', 'success');
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

    // Controles de vista
    document.addEventListener('DOMContentLoaded', function() {
        const gridView = document.getElementById('grid-view');
        const listView = document.getElementById('list-view');
        const productsGrid = document.getElementById('products-grid');

        gridView.addEventListener('click', function() {
            productsGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
            gridView.className = 'p-2 rounded-lg bg-tech-blue text-white';
            listView.className = 'p-2 rounded-lg bg-gray-200 text-gray-600 hover:bg-gray-300';
        });

        listView.addEventListener('click', function() {
            productsGrid.className = 'grid grid-cols-1 gap-4';
            listView.className = 'p-2 rounded-lg bg-tech-blue text-white';
            gridView.className = 'p-2 rounded-lg bg-gray-200 text-gray-600 hover:bg-gray-300';
        });

        // Control del rango de precios
        const priceRange = document.getElementById('price-range');
        const priceValue = document.getElementById('price-value');
        
        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = '$' + this.value;
            });
        }
    });
</script>
@endpush
