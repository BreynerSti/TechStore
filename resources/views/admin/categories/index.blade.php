@extends('layouts.admin')

@section('title', 'Categorías - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Categorías</h1>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center px-5 py-2 bg-tech-blue text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
        <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
        Agregar Categoría
    </a>
</div>

<!-- Buscador y filtros -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form class="flex flex-col md:flex-row gap-4" method="GET" action="">
        <input type="text" name="search" placeholder="Buscar por nombre de categoría"
               class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent w-full md:w-64">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg w-full md:w-48">
            <option value="">Todos los estados</option>
            <option value="active">Activas</option>
            <option value="inactive">Inactivas</option>
        </select>
        <button type="submit"
                class="bg-tech-blue text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
            Buscar
        </button>
    </form>
</div>

<!-- Tabla de categorías -->
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Productos</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Creada</th>
                <th class="px-6 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($categories as $category)
                <tr>
                    <td class="px-6 py-4 text-gray-900 font-semibold">{{ $category->id }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($category->image)
                                <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name }}" class="w-8 h-8 rounded-lg object-cover mr-3">
                            @else
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i data-lucide="folder" class="w-4 h-4 text-blue-600"></i>
                                </div>
                            @endif
                            <span class="font-medium text-gray-900">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ Str::limit($category->description, 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-block px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">{{ $category->products_count }} productos</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($category->is_active)
                            <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">Activa</span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">Inactiva</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-sm">{{ $category->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="inline-block px-4 py-1 bg-blue-100 text-blue-700 font-semibold rounded hover:bg-blue-200 transition">
                            Editar
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
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
                            <i data-lucide="folder-x" class="w-12 h-12 text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">No hay categorías</p>
                            <p class="text-sm">Crea tu primera categoría para comenzar a organizar tus productos</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Paginación -->
<div class="mt-6 flex justify-center">
    {{ $categories->links() }}
</div>

<!-- Estadísticas rápidas -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i data-lucide="folder" class="w-6 h-6 text-blue-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Categorías</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Categorías Activas</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6 text-orange-600"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Productos Totales</p>
                <p class="text-2xl font-bold text-gray-900">{{ \App\Models\Product::count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scripts específicos para la gestión de categorías
</script>
@endpush
