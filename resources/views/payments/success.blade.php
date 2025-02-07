@extends('layouts.masterlayout')

@section('content')

<!-- Componente Navbar -->
<x-navbar />
<x-cart />

<div class="container d-flex flex-column align-items-center mt-5 shadow-lg p-4 bg-body rounded" style="max-width: 60%; min-width: 300px;">
    <div class="text-center mb-1">
        <h1 class="mb-2">Pagamento</h1>
        <h1>Aprovado!</h1>
    </div>

    <div class="ticket-container">
        <!-- Imagem sobreposta (não alterada) -->
        <div class="circle-image animated-rotate">
            <img src="{{ asset('images/icons/ordersucess(2).png') }}" alt="Order Success" class="img-fluid">
        </div>

        <!-- Conteúdo do ticket -->
        <div class="ticket-content">
            @if (isset($orderId) && isset($amount))
            <div class="confirmation-text mt-5">
                <span class="order-id-display">{{ $orderId }}</span>
            </div>
            @endif

            <!-- Botão CTA aprimorado e centralizado -->
            <div class="d-flex justify-content-center my-4">
                <a href="{{ url('/') }}" class="mt-3 btn btn-custom">Continuar Comprando</a>
            </div>

            <!-- Mensagem de agradecimento -->
            <h3 class="thank-you-text">Bom apetite!</h3>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
    /* Container principal */
    .ticket-container {
        width: 100%;
        max-width: 480px;
        background: url("{{ asset('images/icons/ticket.png') }}") no-repeat center/cover;
        padding: 90px 20px;
        margin: 20px auto;
        position: relative;
        text-align: center;
        border-radius: 20px;
        transition: transform 0.3s ease-in-out;
        transform: rotate(15deg);
    }

    /* Efeito picotado na base do ticket */
    .ticket-container::after {
        content: "";
        position: absolute;
        width: 100%;
        height: 30px;
        bottom: -10px;
        left: 0;
        background: radial-gradient(circle at 10% 50%, transparent 8px, white 9px),
            radial-gradient(circle at 30% 50%, transparent 8px, white 9px),
            radial-gradient(circle at 50% 50%, transparent 8px, white 9px),
            radial-gradient(circle at 70% 50%, transparent 8px, white 9px),
            radial-gradient(circle at 90% 50%, transparent 8px, white 9px);
        background-size: 20px 20px;
        background-repeat: repeat-x;
    }

    /* Aumento sutil do ticket no hover */
    .ticket-container:hover {
        transform: rotate(15deg) scale(1.05);
    }

    /* Imagem com círculo */
    .circle-image {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background-color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        position: absolute;
        top: 25px; /* Posição original */
        right: 53%;
        transform: translateX(-50%);
    }

    .circle-image img {
        width: 100%;
        height: auto;
    }

    /* Animação de rotação leve */
    .animated-rotate {
        animation: rotate-small 3s infinite ease-in-out;
    }

    @keyframes rotate-small {
        0% {
            transform: rotate(0deg);
        }

        50% {
            transform: rotate(-15deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    /* Conteúdo do ticket */
    .ticket-content {
        padding: 80px 10px 20px;
    }

    .confirmation-text {
        font-size: 1.6em;
        font-weight: bold;
        color: white;
        margin-bottom: 20px;
        text-align: center;
    }

    .order-id-display {
        padding: 10px 15px;
        border-radius: 8px;
        background-color: rgba(0, 0, 0, 0.22);
    }

    /* Botão CTA aprimorado */
    .btn-custom {
        background: linear-gradient(45deg, #F2D338, #E1C700);
        color: white;
        border: none;
        font-size: 1.2em;
        border-radius: 16px;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, background 0.3s ease;
    }

    .btn-custom:hover {
        background: linear-gradient(45deg, #E1C700, #F2D338);
        transform: translateY(-3px);
    }

    .thank-you-text {
        font-size: 1.2em;
        color: white;
        margin-top: 20px;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .container {
            width: 90%;
            padding: 20px;
        }

        .circle-image {
            width: 140px;
            height: 140px;
            top: 30px;
        }

        .confirmation-text {
            font-size: 1.4em;
        }

        .btn-custom {
            font-size: 1em;
            padding: 10px 20px;
        }

        .thank-you-text {
            font-size: 1em;
        }
    }
</style>

@endsection
