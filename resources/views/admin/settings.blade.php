@extends('layouts.admin')

@section('title', 'Configuración - Panel de Administración')

@section('content')

@if(session('success'))
    @php
        $success = session('success');
        session()->forget('success');
        if (is_array($success)) {
            $success = array_unique($success);
            $success = array_values($success)[0];
        }
    @endphp
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
            {{ $success }}
        </div>
    </div>
@endif

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Configuración</h1>
        <p class="text-gray-600 mt-1">Configuración general de TechStore</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Información de la tienda -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Información de la Tienda</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Nombre de la tienda -->
                <div class="mb-6">
                    <label for="store_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Tienda
                    </label>
                    <input type="text" 
                           id="store_name" 
                           name="store_name" 
                           value="{{ old('store_name', $settings['store_name'] ?? 'TechStore') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                           placeholder="TechStore">
                </div>

                <!-- Descripción -->
                <div class="mb-6">
                    <label for="store_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción de la Tienda
                    </label>
                    <textarea id="store_description" 
                              name="store_description" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                              placeholder="Tu tienda de tecnología de confianza...">{{ old('store_description', $settings['store_description'] ?? 'Tu tienda de tecnología de confianza con los mejores productos al mejor precio.') }}</textarea>
                </div>

                <!-- Email de contacto -->
                <div class="mb-6">
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email de Contacto
                    </label>
                    <input type="email" 
                           id="contact_email" 
                           name="contact_email" 
                           value="{{ old('contact_email', $settings['contact_email'] ?? 'contacto@techstore.com') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                           placeholder="contacto@techstore.com">
                </div>

                <!-- Teléfono -->
                <div class="mb-6">
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Teléfono de Contacto
                    </label>
                    <input type="text" 
                           id="contact_phone" 
                           name="contact_phone" 
                           value="{{ old('contact_phone', $settings['contact_phone'] ?? '+1 (555) 123-4567') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                           placeholder="+1 (555) 123-4567">
                </div>

                <!-- Dirección -->
                <div class="mb-6">
                    <label for="store_address" class="block text-sm font-medium text-gray-700 mb-2">
                        Dirección de la Tienda
                    </label>
                    <textarea id="store_address" 
                              name="store_address" 
                              rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                              placeholder="Dirección completa...">{{ old('store_address', $settings['store_address'] ?? '123 Tech Street, Silicon Valley, CA 94025, Estados Unidos') }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-tech-blue text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                        <i data-lucide="save" class="w-4 h-4 mr-2 inline"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Configuración general -->
    <div class="space-y-6">
        <!-- Configuración de moneda -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Configuración de Moneda</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                            Moneda Principal
                        </label>
                        <select id="currency" 
                                name="currency" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent">
                            <option value="USD" {{ ($settings['currency'] ?? 'USD') === 'USD' ? 'selected' : '' }}>Dólar Estadounidense (USD)</option>
                            <option value="EUR" {{ ($settings['currency'] ?? 'USD') === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                            <option value="GBP" {{ ($settings['currency'] ?? 'USD') === 'GBP' ? 'selected' : '' }}>Libra Esterlina (GBP)</option>
                            <option value="MXN" {{ ($settings['currency'] ?? 'USD') === 'MXN' ? 'selected' : '' }}>Peso Mexicano (MXN)</option>
                            <option value="COP" {{ ($settings['currency'] ?? 'USD') === 'COP' ? 'selected' : '' }}>Peso Colombiano (COP)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="currency_symbol" class="block text-sm font-medium text-gray-700 mb-2">
                            Símbolo de Moneda
                        </label>
                        <input type="text" 
                               id="currency_symbol" 
                               name="currency_symbol" 
                               value="{{ old('currency_symbol', $settings['currency_symbol'] ?? '$') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-tech-blue focus:border-transparent"
                               placeholder="$">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-tech-blue text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Información del sistema -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Información del Sistema</h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Versión del Sistema</h3>
                <p class="text-gray-600">TechStore v1.0.0</p>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Última Actualización</h3>
                <p class="text-gray-600">{{ \Carbon\Carbon::parse(config('app.last_update'))->diffForHumans() }}</p>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 mb-2">Estado del Sistema</h3>
                <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-700 rounded-full">Operativo</span>
            </div>
        </div>
    </div>
</div>
@endsection

 