<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Mostrar página de configuración
     */
    public function index()
    {
        // Obtener configuración actual desde cache o valores por defecto
        $settings = Cache::get('store_settings', [
            'store_name' => 'TechStore',
            'store_description' => 'Tu tienda de tecnología de confianza con los mejores productos al mejor precio.',
            'contact_email' => 'contacto@techstore.com',
            'contact_phone' => '+1 (555) 123-4567',
            'store_address' => '123 Tech Street, Silicon Valley, CA 94025, Estados Unidos',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'timezone' => 'America/New_York',
            'maintenance_mode' => false,
        ]);

        return view('admin.settings', compact('settings'));
    }

    /**
     * Actualizar configuración
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'nullable|string|max:255',
            'store_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:30',
            'store_address' => 'nullable|string|max:500',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:5',
            'timezone' => 'nullable|string|max:50',
        ]);

        // Obtener configuración actual
        $currentSettings = Cache::get('store_settings', []);
        
        // Actualizar con nuevos valores
        $updatedSettings = array_merge($currentSettings, $validated);
        
        // Guardar en cache
        Cache::put('store_settings', $updatedSettings, now()->addDays(30));

        return redirect()->route('admin.settings')
            ->with('success', 'Configuración actualizada exitosamente.');
    }

    /**
     * Toggle modo mantenimiento
     */
    public function toggleMaintenance(Request $request)
    {
        $maintenanceMode = $request->boolean('maintenance_mode');
        
        $currentSettings = Cache::get('store_settings', []);
        $currentSettings['maintenance_mode'] = $maintenanceMode;
        
        Cache::put('store_settings', $currentSettings, now()->addDays(30));

        return response()->json([
            'success' => true,
            'maintenance_mode' => $maintenanceMode,
            'message' => $maintenanceMode ? 'Modo mantenimiento activado' : 'Modo mantenimiento desactivado'
        ]);
    }
} 