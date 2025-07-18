<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $shipping = $total >= 50 ? 0 : 5.99;
        $grandTotal = $total + $shipping;
        return view('pages.checkout', compact('cart', 'total', 'shipping', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $cart = Session::get('cart', []);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Tu carrito está vacío');
        }

        // Calcular total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {
            // Crear el pedido
            $order = Order::create([
                'user_id' => auth()->id() ?? null,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal' => $total,
                'total' => $total,
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_postal_code' => $request->postal_code,
                'shipping_phone' => $request->phone,
                'shipping_name' => $request->name,
                'shipping_email' => $request->email,
                'notes' => $request->notes,
            ]);

            // Crear los items del pedido
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
                // Actualizar stock del producto
                $product = \App\Models\Product::find($item['id']);
                if ($product) {
                    $product->stock -= $item['quantity'];
                    if ($product->stock < 0) $product->stock = 0;
                    $product->save();
                }
            }

            // Limpiar el carrito
            Session::forget('cart');

            return redirect()->route('checkout.success', $order->id)
                           ->with('success', '¡Pedido realizado con éxito!');

        } catch (\Exception $e) {
          
            // return back()->with('error', 'Error al procesar el pedido. Por favor, intenta de nuevo.');
        }
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);
        return view('pages.checkout-success', compact('order'));
    }
} 