<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel de Administración - TechStore')</title>
    
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
<body class="font-tech bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
        <div class="flex justify-between items-center h-16 px-4 sm:px-6 lg:px-8">
            <!-- Logo y título -->
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-tech-blue to-purple-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="settings" class="w-5 h-5 text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">TechStore Admin</span>
                </a>
            </div>

            <!-- Acciones del usuario -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-tech-blue transition-colors" title="Ver sitio web">
                    <i data-lucide="external-link" class="w-5 h-5"></i>
                </a>
                <div class="relative" id="admin-user-menu">
                    <button class="flex items-center space-x-2 text-gray-700 hover:text-tech-blue transition-colors" id="admin-user-menu-button">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span>Administrador</span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    
                    <!-- Dropdown del usuario -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden" id="admin-user-dropdown">
                        <div class="py-1">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                                Mi Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    <i data-lucide="log-out" class="w-4 h-4 inline mr-2"></i>
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="fixed left-0 top-16 h-full w-64 bg-white shadow-lg z-40">
        <nav class="mt-8">
            <div class="px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-tech-blue text-white' : '' }}">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.products.*') ? 'bg-tech-blue text-white' : '' }}">
                    <i data-lucide="package" class="w-5 h-5"></i>
                    <span class="font-medium">Productos</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-tech-blue text-white' : '' }}">
                    <i data-lucide="folder" class="w-5 h-5"></i>
                    <span class="font-medium">Categorías</span>
                </a>
                
                <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.orders.*') ? 'bg-tech-blue text-white' : '' }}">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="font-medium">Pedidos</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-tech-blue text-white' : '' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    <span class="font-medium">Usuarios</span>
                </a>
                
                <a href="{{ route('admin.settings') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-tech-blue text-white' : '' }}">
                    <i data-lucide="settings" class="w-5 h-5"></i>
                    <span class="font-medium">Configuración</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Contenido Principal -->
    <main class="ml-64 pt-16 min-h-screen">
        <div class="p-6">
            <!-- Breadcrumbs -->
            @if(isset($breadcrumbs))
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-tech-blue">Dashboard</a></li>
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                            @if($breadcrumb['url'])
                                <a href="{{ $breadcrumb['url'] }}" class="hover:text-tech-blue">{{ $breadcrumb['title'] }}</a>
                            @else
                                <span class="text-gray-900">{{ $breadcrumb['title'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
            @endif

            <!-- Mensajes de éxito/error -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contenido de la página -->
            @yield('content')
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        // Inicializar iconos Lucide
        lucide.createIcons();

        // Toggle del menú de usuario admin
        document.getElementById('admin-user-menu-button').addEventListener('click', function() {
            const dropdown = document.getElementById('admin-user-dropdown');
            dropdown.classList.toggle('hidden');
        });

        // Cerrar dropdowns al hacer clic fuera
        document.addEventListener('click', function(e) {
            const userMenu = document.getElementById('admin-user-menu');
            const userDropdown = document.getElementById('admin-user-dropdown');
            
            if (!userMenu.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Configuración global para requests AJAX
        window.apiUrl = '{{ url('/api') }}';
        
        // Token CSRF para requests
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>

    @stack('scripts')
</body>
</html>
