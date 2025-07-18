@extends('layouts.admin')

@section('title', 'Crear Producto - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Crear Nuevo Producto</h1>
    <a href="{{ route('admin.products.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow hover:bg-gray-600 transition">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Volver a Productos
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6">
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Información básica -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Básica</h3>
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto</label>
                    <input type="text" id="name" name="name" required
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                </div>
                
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" id="sku" name="sku" required
                           value="{{ old('sku') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">{{ old('description') }}</textarea>
                </div>
                
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select id="category_id" name="category_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Precio y stock -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Precio y Stock</h3>
                
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">$</span>
                        <input type="number" id="price" name="price" step="0.01" min="0" required
                               value="{{ old('price') }}"
                               class="w-full pl-8 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                    </div>
                </div>
                
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" required
                           value="{{ old('stock') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                </div>
                
                <div>
                    <label for="is_active" class="flex items-center">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-tech-blue focus:ring-tech-blue border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Producto activo</span>
                    </label>
                </div>
                
                <div>
                    <label for="is_featured" class="flex items-center">
                        <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                               class="h-4 w-4 text-tech-blue focus:ring-tech-blue border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Producto destacado</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Imágenes -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Imágenes del Producto</h3>
            <div class="space-y-4">
                <div>
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Subir Imágenes</label>
                    <input type="file" id="images" name="images[]" multiple accept="image/*"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Puedes seleccionar múltiples imágenes. La primera será la imagen principal.</p>
                </div>
            </div>
        </div>
        
        <!-- Botones de acción -->
        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.products.index') }}"
               class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                Cancelar
            </a>
            <button type="submit"
                    class="px-6 py-2 bg-tech-blue text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                Crear Producto
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Scripts específicos para la creación de productos
    console.log('Formulario de creación de producto cargado');
</script>
@endpush
