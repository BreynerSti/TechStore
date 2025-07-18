<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TechStore - Tecnología de Vanguardia')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Configuración personalizada de Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'tech-blue': '#0066cc',
                        'tech-dark': '#1a202c',
                        'tech-gray': '#2d3748'
                    },
                    fontFamily: {
                        'tech': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Iconos Lucide -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    @stack('styles')
</head>

<body class="font-tech bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-tech-blue to-purple-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-xl font-bold text-gray-900">TechStore</span>
                    </a>
                </div>

                <!-- Navegación Desktop -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-700 hover:text-tech-blue font-medium transition-colors">
                        Inicio
                    </a>
                    <a href="{{ route('products') }}"
                        class="text-gray-700 hover:text-tech-blue font-medium transition-colors">
                        Productos
                    </a>
                    @auth
                    <a href="{{ route('profile') }}" class="text-gray-700 hover:text-tech-blue font-medium transition-colors">
                        Perfil
                    </a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin') }}" class="text-yellow-600 hover:text-yellow-800 font-medium transition-colors">
                            Admin
                        </a>
                    @endif
                    @endauth
                </nav>

                <!-- Acciones del usuario -->
                <div class="flex items-center space-x-4">
                    <!-- Búsqueda -->
                    <div class="hidden sm:block relative">
                        <input type="text" id="search" placeholder="Buscar productos..."
                            class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                        <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Carrito -->
                    <a href="{{ route('cart') }}"
                        class="relative p-2 text-gray-700 hover:text-tech-blue transition-colors" id="cart-toggle">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
                            id="cart-count">
                            0
                        </span>
                    </a>

                    <!-- Usuario -->
                    <div class="relative" id="user-menu">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-tech-blue transition-colors"
                            id="user-menu-button">
                            <i data-lucide="user" class="w-6 h-6"></i>
                            <span class="hidden sm:block">Cuenta</span>
                        </button>

                        <!-- Dropdown del usuario -->
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden"
                            id="user-dropdown">
                            <div class="py-1">
                                @auth
                                    <a href="{{ route('profile') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                        Mi Perfil
                                    </a>
                                    <a href="{{ route('my-orders') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i data-lucide="package" class="w-4 h-4 inline mr-2"></i>
                                        Mis Pedidos
                                    </a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Iniciar Sesión
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Registrarse
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Menú móvil -->
                    <button class="md:hidden p-2 text-gray-700" id="mobile-menu-button">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú móvil -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-tech-blue">Inicio</a>
                <a href="{{ route('products') }}"
                    class="block px-3 py-2 text-gray-700 hover:text-tech-blue">Productos</a>
                
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin') }}" class="block px-3 py-2 text-yellow-600 hover:text-yellow-800">Admin</a>
                    @endif
                    <hr class="my-2">
                    <a href="{{ route('profile') }}" class="block px-3 py-2 text-gray-700 hover:text-tech-blue">Mi Perfil</a>
                    <a href="{{ route('my-orders') }}" class="block px-3 py-2 text-gray-700 hover:text-tech-blue">Mis Pedidos</a>
                    <hr class="my-2">
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-red-600 hover:text-red-700">Cerrar Sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-tech-blue">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700 hover:text-tech-blue">Registrarse</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            @include('components.alerts')
        </div>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-tech-dark text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Información de la empresa -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-tech-blue to-purple-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                        </div>
                        <span class="text-xl font-bold">TechStore</span>
                    </div>
                    <p class="text-gray-300 mb-4">
                        Tu tienda de tecnología de confianza. Ofrecemos los mejores productos
                        tecnológicos con garantía y servicio al cliente excepcional.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="twitter" class="w-5 h-5"></i>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Enlaces rápidos -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces Rápidos</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Productos</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Categorías</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Nuevos</a></li>
                    </ul>
                </div>

                <!-- Soporte -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Soporte</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contacto</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Garantías</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-300 hover:text-white transition-colors">Devoluciones</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">
                    © {{ date('Y') }} TechStore. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Inicializar iconos Lucide
        lucide.createIcons();

        // Toggle del menú de usuario
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        });

        // Toggle del menú móvil
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('user-menu');
            const userDropdown = document.getElementById('user-dropdown');

            if (!userMenu.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Configuración global para requests AJAX
        window.apiUrl = '{{ url('/api') }}';

        // Token CSRF para requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Función para actualizar contador del carrito
        window.updateCartCount = function() {
            fetch('/cart/items')
            .then(response => response.json())
            .then(data => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) {
                    const count = data.items.length;
                    cartCount.textContent = count;
                    if (count > 0) {
                        cartCount.classList.remove('hidden');
                    } else {
                        cartCount.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
        };

        // Actualizar contador del carrito al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>

    @stack('scripts')
</body>

</html>
