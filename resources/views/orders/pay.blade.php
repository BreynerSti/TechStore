@extends('layouts.app')

@section('title', 'Pagar Pedido - TechStore')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-2xl font-bold mb-6">Pagar Pedido #{{ $order->order_number }}</h1>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <p><strong>Total a pagar:</strong> <span class="text-tech-blue font-semibold">${{ number_format($order->total, 2) }}</span></p>
        <p><strong>Estado actual:</strong> <span class="font-semibold">{{ ucfirst($order->status) }}</span></p>
    </div>
    <div id="paypal-button-container" class="mb-6"></div>
    <div id="paypal-success-message" class="hidden bg-green-100 text-green-800 rounded p-4 text-center mt-4">
        ¡Pago realizado con éxito! Gracias por tu compra.
    </div>
    <div class="mt-8 text-center">
        <a href="{{ url()->previous() }}" class="inline-block bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">Volver</a>
    </div>
</div>

<!-- PayPal SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AY3cnlP5Wqht_mp4fb3flwPTWlfVvAknXMO46nxcWNzbzoShR0D9fVFqTCP1zmWaeRbP6E24erheCItO&currency=USD"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '{{ number_format($order->total, 2, ".", "") }}' // Total del pedido
                    },
                    description: 'Pago de pedido #{{ $order->order_number }}'
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Detectar el método de pago real
                let paymentMethod = 'paypal';
                if (details.payment_source) {
                    if (details.payment_source.card) {
                        paymentMethod = 'card';
                    } else if (details.payment_source.paypal) {
                        paymentMethod = 'paypal';
                    }
                }
                // Llamar al backend para marcar el pedido como pagado
                fetch("{{ route('orders.pay.confirm', $order->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                    },
                    body: JSON.stringify({ paypal_order_id: data.orderID, payment_method: paymentMethod })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('paypal-success-message').classList.remove('hidden');
                    } else {
                        alert('El pago fue procesado pero no se pudo actualizar el pedido.');
                    }
                })
                .catch(() => {
                    alert('El pago fue procesado pero ocurrió un error al actualizar el pedido.');
                });
            });
        },
        onError: function(err) {
            alert('Ocurrió un error con el pago en PayPal. Intenta de nuevo.');
        }
    }).render('#paypal-button-container');
});
</script>
@endsection 