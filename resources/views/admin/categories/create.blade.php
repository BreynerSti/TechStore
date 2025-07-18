@extends('layouts.admin')

@section('title', 'Crear Categoría - Panel de Administración')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Crear Nueva Categoría</h1>
        <p class="text-gray-600 mt-1">Agrega una nueva categoría para organizar tus productos</p>
    </div>
    <a href="{{ route('admin.categories.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg shadow hover:bg-gray-200 transition">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
        Volver a Categorías
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Formulario principal -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Información de la Categoría</h2>
            </div>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                <!-- Nombre de la categoría -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent @error('name') border-red-500 @enderror"
                           placeholder="Ej: Smartphones, Laptops, Audio..."
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Describe brevemente qué productos incluye esta categoría...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Imagen -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Imagen de la Categoría
                    </label>
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent @error('image') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Formatos permitidos: JPEG, PNG, JPG, GIF. Máximo 2MB.</p>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="mb-6">
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                        Estado
                    </label>
                    <select id="is_active" 
                            name="is_active" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                        <option value="1" {{ old('is_active', true) ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ old('is_active', true) ? '' : 'selected' }}>Inactiva</option>
                    </select>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-tech-blue text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i>
                        Crear Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Panel lateral -->
    <div class="lg:col-span-1">
        <!-- Información útil -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Consejos</h3>
            <ul class="space-y-3 text-sm text-gray-600">
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>Usa nombres descriptivos y claros</span>
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>El slug se genera automáticamente</span>
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>Elige un icono representativo</span>
                </li>
                <li class="flex items-start">
                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                    <span>Las categorías activas aparecen en el frontend</span>
                </li>
            </ul>
        </div>

        {{-- Eliminada la sección de Vista Previa al crear categoría --}}
    </div>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush
