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
    <h1 class="text-center">Pagamento MB WAY Pendente</h1>

    <div class="alert alert-warning text-center">
        <p>Seu pagamento MB WAY ainda não foi confirmado.</p>
        <p>Por favor, aguarde alguns minutos e tente novamente.</p>
        <a href="{{ route('payment.mbway.status', ['orderId' => $orderId]) }}" class="btn btn-primary">Verificar Novamente o Status</a>
    </div>
</div>


@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ...></script>
