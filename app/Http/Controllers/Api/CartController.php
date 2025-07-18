<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Agregar producto al carrito
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Verificar stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente para este producto'
            ], 400);
        }

        // Obtener carrito de la sesión
        $cart = session()->get('cart', []);
        
        // Si el producto ya está en el carrito, incrementar cantidad
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] += $request->quantity;
        } else {
            // Agregar nuevo producto al carrito
            $cart[$request->product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->images->first() ? $product->images->first()->image_path : null
            ];
        }

        // Guardar carrito en sesión
        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado al carrito',
            'cart_count' => $this->getCartCount()
        ]);
    }

    /**
     * Obtener cantidad de items en el carrito
     */
    public function count(): JsonResponse
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Obtener items del carrito
     */
    public function items(): JsonResponse
    {
        $cart = session()->get('cart', []);
        $items = [];
        $total = 0;

        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            $items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
                'image' => $item['image']
            ];
        }

        return response()->json([
            'items' => $items,
            'total' => $total,
            'subtotal' => $total,
            'count' => $this->getCartCount()
        ]);
    }

    /**
     * Actualizar cantidad de un producto en el carrito
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Verificar stock
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stock insuficiente para este producto'
            ], 400);
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado en el carrito'
        ], 404);
    }

    /**
     * Remover producto del carrito
     */
    public function remove($id): JsonResponse
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'message' => 'Producto removido del carrito',
                'cart_count' => $this->getCartCount()
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Producto no encontrado en el carrito'
        ], 404);
    }

    /**
     * Limpiar carrito
     */
    public function clear(): JsonResponse
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Carrito limpiado',
            'cart_count' => 0
        ]);
    }

    /**
     * Obtener cantidad total de items en el carrito
     */
    private function getCartCount(): int
    {
        $cart = session()->get('cart', []);
        $count = 0;
        
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }
} 