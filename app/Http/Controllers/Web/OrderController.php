<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Mostrar listado de pedidos en el admin
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user']);

        // Filtro por estado
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Búsqueda por número de pedido, email, nombre o ID de usuario (ignorando mayúsculas/minúsculas y espacios)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where('order_number', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Estadísticas para el admin (agrupadas)
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::whereIn('status', ['processing', 'paid'])->count(),
            'shipped' => Order::whereIn('status', ['shipped', 'delivered'])->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Mostrar detalles de un pedido
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mostrar formulario de edición (cambio de estado, etc)
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Actualizar pedido (cambiar estado, notas, etc)
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string',
            'payment_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Pedido actualizado exitosamente.');
    }

    /**
     * Eliminar pedido (opcional)
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')
            ->with('success', 'Pedido eliminado exitosamente.');
    }

    /**
     * Mostrar pantalla de pago para un pedido pendiente
     */
    public function pay(Order $order)
    {
        // Aquí luego irá la lógica de integración con PayPal
        return view('orders.pay', compact('order'));
    }

    /**
     * Confirmar el pago de un pedido (AJAX desde PayPal)
     */
    public function confirmPayment(Order $order, Request $request)
    {
        try {
            $order->status = 'processing'; // Estado permitido
            $order->payment_status = 'paid';
            $order->payment_method = $request->input('payment_method', 'paypal');
            $order->save();
            return response()->json(['success' => true, 'message' => 'Pedido marcado como pagado.']);
        } catch (\Exception $e) {
            Log::error('Error al marcar pedido como pagado: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'exception' => $e
            ]);
            return response()->json(['success' => false, 'message' => 'Error al actualizar el pedido.', 'error' => $e->getMessage()], 500);
        }
    }
}
