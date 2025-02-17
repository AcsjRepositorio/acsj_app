<!-- resources/views/payments/multibanco_reference.blade.php -->
@extends('layouts.masterlayout')

@section('content')

<!-- Componente Navbar -->
<x-navbar />
<x-cart />

<div class="container mt-4">
    <h1 class="text-center">Pagamento por Multibanco</h1>
    <div class="alert alert-info text-center">
        <p><strong>Pedido:</strong> {{ $orderId }}</p>
        <p><strong>Valor:</strong> €{{ number_format($amount, 2) }}</p>
        <p><strong>Entidade:</strong> {{ $entity }}</p>
        <p><strong>Referência:</strong> {{ $reference }}</p>
        <p><strong>Expira em:</strong> {{ $expiryDate }}</p>
        <p class="mt-3">
            Por favor, dirija-se a um terminal ou utilize o homebanking para efetuar o pagamento utilizando os dados acima.
            Seu pedido ficará pendente até que o pagamento seja confirmado.
        </p>
    </div>
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ...></script>

