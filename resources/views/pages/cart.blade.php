@extends('layouts.app')

@section('title', 'Carrito de Compras - TechStore')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Carrito de Compras</h1>
        <p class="text-gray-600">Revisa y gestiona los productos en tu carrito</p>
    </div>

    <!-- Contenido del carrito -->
    <div id="cart-content">
        <!-- Loading state -->
        <div id="cart-loading" class="text-center py-12">
            <div class="inline-flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-tech-blue"></div>
                <span class="text-gray-600">Cargando carrito...</span>
            </div>
        </div>

        <!-- Carrito vacío -->
        <div id="cart-empty" class="hidden text-center py-16">
            <div class="max-w-md mx-auto">
                <i data-lucide="shopping-cart" class="w-24 h-24 text-gray-400 mx-auto mb-6"></i>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Tu carrito está vacío</h2>
                <p class="text-gray-600 mb-8">Parece que aún no has agregado productos a tu carrito. ¡Comienza a explorar nuestros productos!</p>
                <a href="{{ route('products') }}" class="bg-tech-blue text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-flex items-center space-x-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span>Ver Productos</span>
                </a>
            </div>
        </div>

        <!-- Carrito con productos -->
        <div id="cart-items" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Lista de productos -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Productos en el carrito</h2>
                        </div>
                        
                        <div id="cart-items-list" class="divide-y divide-gray-200">
                            <!-- Los items se cargarán dinámicamente aquí -->
                        </div>
                    </div>
                </div>

                <!-- Resumen del pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Resumen del pedido</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-medium">$0.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Envío:</span>
                                <span id="shipping" class="font-medium">$0.00</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-900">Total:</span>
                                    <span id="total" class="text-2xl font-bold text-tech-blue">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="space-y-3">
                            <button onclick="proceedToCheckout()" 
                                    class="w-full bg-tech-blue text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                                <i data-lucide="credit-card" class="w-5 h-5"></i>
                                <span>Proceder al Pago</span>
                            </button>
                            
                            <button onclick="clearCart()" 
                                    class="w-full bg-gray-100 text-gray-700 py-2 px-6 rounded-lg font-medium hover:bg-gray-200 transition-colors flex items-center justify-center space-x-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                <span>Vaciar Carrito</span>
                            </button>
                        </div>

                        <!-- Información adicional -->
                        <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                                <span>Pago seguro</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i data-lucide="truck" class="w-4 h-4"></i>
                                <span>Envío gratuito en compras > $50</span>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                                <span>Devolución gratuita</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Continuar comprando -->
            <div class="mt-8 text-center">
                <a href="{{ route('products') }}" 
                   class="inline-flex items-center space-x-2 text-tech-blue font-semibold hover:text-blue-700 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    <span>Seguir comprando</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let cartData = {
        items: [],
        total: 0,
        subtotal: 0,
        shipping: 0
    };

    // Cargar carrito al iniciar la página
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });

    // Función para cargar el carrito
    function loadCart() {
        fetch('/cart/items')
        .then(response => response.json())
        .then(data => {
            cartData = data;
            updateCartDisplay();
            updateCartCount();
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            showNotification('Error al cargar el carrito', 'error');
        });
    }

    // Función para actualizar la visualización del carrito
    function updateCartDisplay() {
        const loading = document.getElementById('cart-loading');
        const empty = document.getElementById('cart-empty');
        const items = document.getElementById('cart-items');
        const itemsList = document.getElementById('cart-items-list');

        loading.classList.add('hidden');

        if (cartData.items.length === 0) {
            empty.classList.remove('hidden');
            items.classList.add('hidden');
        } else {
            empty.classList.add('hidden');
            items.classList.remove('hidden');
            renderCartItems();
            updateTotals();
        }
    }

    // Función para renderizar los items del carrito
    function renderCartItems() {
        const itemsList = document.getElementById('cart-items-list');
        itemsList.innerHTML = '';

        cartData.items.forEach(item => {
            const itemElement = createCartItemElement(item);
            itemsList.appendChild(itemElement);
        });
    }

    // Función para crear elemento de item del carrito
    function createCartItemElement(item) {
        const div = document.createElement('div');
        div.className = 'p-6';
        div.innerHTML = `
            <div class="flex items-center space-x-4">
                <!-- Imagen del producto -->
                <div class="flex-shrink-0">
                    <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden">
                        ${item.image ? 
                            `<img src="/storage/${item.image}" alt="${item.name}" class="w-full h-full object-cover">` :
                            `<i data-lucide="image" class="w-full h-full flex items-center justify-center text-gray-400"></i>`
                        }
                    </div>
                </div>

                <!-- Información del producto y botón eliminar -->
                <div class="flex-1 min-w-0 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 truncate flex items-center">
                            ${item.name}
                            <button onclick="removeFromCart(${item.id})" 
                                class="ml-4 text-red-600 hover:text-white bg-red-100 hover:bg-red-600 rounded-full p-2 transition-colors text-xl font-bold flex items-center justify-center"
                                title="Eliminar del carrito">
                                <svg xmlns='http://www.w3.org/2000/svg' class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                            </button>
                        </h3>
                        <p class="text-sm text-gray-500">Precio unitario: $${Number(item.price).toFixed(2)}</p>
                    </div>
                </div>

                <!-- Controles de cantidad -->
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity(${item.id}, -1)" 
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i data-lucide="minus" class="w-4 h-4"></i>
                    </button>
                    <span class="w-12 text-center font-medium">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)" 
                            class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Subtotal -->
                <div class="text-right">
                    <div class="text-lg font-semibold text-gray-900">$${Number(item.subtotal).toFixed(2)}</div>
                </div>
            </div>
        `;
        return div;
    }

    // Función para actualizar cantidad
    function updateQuantity(productId, delta) {
        const item = cartData.items.find(item => item.id === productId);
        if (!item) return;

        const newQuantity = item.quantity + delta;
        if (newQuantity < 1) return;

        fetch('/cart/update', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadCart(); // Recargar carrito completo
            } else {
                showNotification(data.message || 'Error al actualizar cantidad', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al actualizar cantidad', 'error');
        });
    }

    // Función para eliminar del carrito
    function removeFromCart(productId) {
        if (!confirm('¿Estás seguro de que quieres eliminar este producto del carrito?')) {
            return;
        }

        fetch(`/cart/remove/${productId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Producto eliminado del carrito', 'success');
                loadCart(); // Recargar carrito completo
            } else {
                showNotification(data.message || 'Error al eliminar producto', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al eliminar producto', 'error');
        });
    }

    // Función para vaciar carrito
    function clearCart() {
        if (!confirm('¿Estás seguro de que quieres vaciar todo el carrito?')) {
            return;
        }

        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Carrito vaciado', 'success');
                loadCart(); // Recargar carrito completo
            } else {
                showNotification(data.message || 'Error al vaciar carrito', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al vaciar carrito', 'error');
        });
    }

    // Función para actualizar totales
    function updateTotals() {
        const subtotal = cartData.subtotal || 0;
        const shipping = subtotal >= 50 ? 0 : 5.99; // Envío gratuito en compras > $50
        const total = subtotal + shipping;

        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('shipping').textContent = shipping === 0 ? 'Gratis' : `$${shipping.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
    }

    // Función para aplicar cupón
    function applyCoupon() {
        const couponCode = document.getElementById('coupon-code').value.trim();
        if (!couponCode) {
            showNotification('Por favor ingresa un código de descuento', 'error');
            return;
        }

        showNotification('Función de cupones próximamente', 'info');
    }

    // Función para proceder al checkout
    function proceedToCheckout() {
        if (cartData.items.length === 0) {
            showNotification('Tu carrito está vacío', 'error');
            return;
        }

        // Verificar si el usuario está autenticado
        @auth
            window.location.href = '{{ route("checkout") }}';
        @else
            showNotification('Para completar tu compra, necesitas iniciar sesión', 'error');
            setTimeout(() => {
                window.location.href = '{{ route("login") }}';
            }, 2000);
        @endauth
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

    // Función para actualizar el contador del carrito
    function updateCartCount() {
        fetch('/cart/items')
            .then(response => response.json())
            .then(data => {
                // Mostrar la cantidad de productos únicos
                const count = data.items.length;
                document.getElementById('cart-count').textContent = count;
            });
    }
</script>
@endpush
