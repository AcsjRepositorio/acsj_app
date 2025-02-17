@extends('layouts.masterlayout')

@section('content')

<!-- Componente Navbar -->
<x-navbar />
<x-cart />

<div class="container mt-4">


    <div class="col-auto text-center">
      
            <img src="{{ asset('images/paymentmethods/mbway.png') }}"
                alt="MBWay"
                class="payment-icon">
        </div>


        <div class="alert alert-info text-center">
            <p>Seu pagamento MB WAY foi iniciado com sucesso!</p>
            <p><strong>Valor:</strong> €{{ number_format($amount, 2) }}</p>
            <p>Aguarde alguns minutos enquanto o pagamento é confirmado na sua app MB WAY.</p>
            <p>Quando estiver pronto, clique no botão abaixo para verificar o status do seu pagamento.</p>
            <!-- Note: O orderId é passado na query para a rota de verificação, mas não é exibido na tela -->
            <a href="{{ route('payment.mbway.status', ['orderId' => $orderId]) }}" class="btn btn-primary">Verificar Status</a>
        </div>
    </div>
    @endsection
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ...></script>
