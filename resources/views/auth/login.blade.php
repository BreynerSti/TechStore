@extends('layouts.app')

@section('title', 'Iniciar Sesión - TechStore')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex flex-col items-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-tech-blue to-purple-600 rounded-full flex items-center justify-center shadow-lg mb-4">
                    <i data-lucide="zap" class="w-8 h-8 text-white"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Iniciar Sesión</h2>
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="font-medium text-tech-blue hover:text-blue-700">
                        Regístrate aquí
                    </a>
                </p>
            </div>
            <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email') }}"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-tech-blue focus:border-tech-blue transition @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-tech-blue focus:border-tech-blue transition @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 text-tech-blue focus:ring-tech-blue border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-900">Recordarme</span>
                    </label>
                </div>
                <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-tech-blue to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-purple-700 transition">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Scripts específicos para la página de login
</script>
@endpush
