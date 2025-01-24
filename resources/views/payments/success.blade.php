

<!-- resources/views/payments/success.blade.php -->

<div class="container">
    <h1>Pagamento Confirmado!</h1>

    <p>Sua compra foi realizada com sucesso.</p>

    @if (isset($orderId) && isset($amount))
        <p><strong>Order ID:</strong> {{ $orderId }}</p>
        <p><strong>Valor Pago:</strong> €{{ $amount }}</p>
    @endif

    <p>Obrigado por comprar conosco!</p>

    <a href="{{ url('/') }}" class="btn btn-primary">Voltar à Página Inicial</a>
</div>


