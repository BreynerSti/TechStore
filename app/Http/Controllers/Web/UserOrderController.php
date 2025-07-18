<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class UserOrderController extends Controller
{
    public function index()
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para ver tus pedidos.');
        }

        $orders = auth()->user()->orders()
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.my-orders', compact('orders'));
    }

    public function show(Order $order)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para ver este pedido.');
        }

        // Verificar que el pedido pertenece al usuario autenticado
        if ($order->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        // Cargar las relaciones necesarias
        $order->load(['orderItems.product', 'orderItems.product.images']);

        return view('pages.order-detail', compact('order'));
    }
} 