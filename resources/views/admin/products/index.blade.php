@extends('layouts.admin')

@section('title', 'Productos - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Productos</h1>
    <a href="{{ route('admin.products.create') }}"
       class="inline-flex items-center px-5 py-2 bg-tech-blue text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
        Agregar Producto
    </a>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Productos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Activos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i data-lucide="pause-circle" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Inactivos</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['inactive'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Stock Bajo</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['low_stock'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Buscador y filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <form class="flex flex-col md:flex-row gap-4 w-full" method="GET" action="{{ route('admin.products.index') }}">
        <input type="text" name="search" placeholder="Buscar por nombre o SKU" value="{{ request('search') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent w-full md:w-64">
        <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg w-full md:w-48">
            <option value="">Todas las categorías</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg w-full md:w-48">
            <option value="">Todos los estados</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
        </select>
        <button type="submit"
                class="bg-tech-blue text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Buscar
        </button>
    </form>
</div>

<!-- Tabla de productos -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
                <tr>
                    <td class="px-6 py-4 text-gray-900 font-semibold">{{ $product->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover mr-3">
                            @elseif($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-10 h-10 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                    <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                </div>
                            @endif
                            <div>
                                <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                @if($product->sku)
                                    <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->category)
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                                  style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }};">
                                {{ $product->category->name }}
                            </span>
                        @else
                            <span class="text-gray-400">Sin categoría</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">${{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold {{ $product->stock < 10 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $product->stock }}
                        </span>
                        @if($product->stock < 10)
                            <span class="text-xs text-red-500 ml-1">¡Stock bajo!</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                Activo
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                Inactivo
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-block px-4 py-1 bg-blue-100 text-blue-700 font-semibold rounded hover:bg-blue-200 transition">
                            Editar
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este producto?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-block px-4 py-1 bg-red-100 text-red-700 font-semibold rounded hover:bg-red-200 transition">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <i data-lucide="package" class="w-12 h-12 text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">No hay productos</p>
                            <p class="text-sm">Comienza agregando tu primer producto</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
@if($products->hasPages())
    <div class="mt-6 flex justify-center">
        {{ $products->links() }}
    </div>
@endif
@endsection

@push('scripts')
<script>
    // Scripts específicos para la gestión de productos
</script>
@endpush
