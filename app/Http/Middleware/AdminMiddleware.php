<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado',
                'error' => 'Debes iniciar sesión para acceder a esta funcionalidad'
            ], 401);
        }

        // 2. Obtener el usuario autenticado
        $user = Auth::user();

        // 3. Verificar que el usuario tenga rol de administrador
        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado',
                'error' => 'No tienes permisos de administrador para realizar esta acción'
            ], 403);
        }

        // 4. Si todo está bien, continuar con la petición
        return $next($request);
    }
}