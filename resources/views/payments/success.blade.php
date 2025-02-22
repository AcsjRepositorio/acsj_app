@extends('layouts.masterlayout')

@section('content')

<!-- Componente Navbar -->
<x-navbar />
<x-cart />



<div class="container d-flex flex-column align-items-center mt-5 shadow-lg p-5 bg-body rounded" style="max-width: 50%; min-width: 300px;">
    <div class="d-flex flex-column flex-md-row align-items-center">
        
        <!-- Imagem -->
        <div class="col-md-6 text-center mb-4 mb-md-0">
            <img src="{{ asset('images/icons/ordersucess.png') }}" alt="Order Success" class="img-fluid animated-image" style="max-width: 250px;">
        </div>

        <!-- Texto -->
        <div class="d-flex flex-column align-items-center text-center">
            <h2 class="fw-bold" style="margin-bottom: 16px;">Pagamento Aprovado!</h2>
            <h5 class="mb-2">Sua senha é:</h5>
            
            @if (isset($orderId))
                <div class="ordernumber">{{ $orderId }}</div>
            @endif

            <a href="{{ url('/') }}" class="btn btn-custom mt-3">Continuar a comprar</a>
        </div>
    </div>
</div>

<x-footer />

<style>
.ordernumber {
    margin-top: 8px;
    height: 40px;
    width: 200px;
    background-color: #156064;
    color: #ffffff;
    font-weight: bold;
    border-radius: 8px;
    padding: 8px;
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-custom {
    color: #F25F29;
    border: 2px solid #F25F29;
    font-size: 1.1em;
    border-radius: 8px;
    padding: 10px 20px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.2s;
}

.btn-custom:hover {
    background-color: #F25F29;
    color: #ffffff;
}

.animated-image {
    animation: rotate-image 2s infinite alternate ease-in-out;
}

@keyframes rotate-image {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(-15deg);
    }
}
</style>
@endsection