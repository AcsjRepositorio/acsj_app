<!-- resources/views/components/navbar.blade.php -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/acsj_logo.png') }}" alt="Logo" style="height: 60px;">
        </a>

        <!-- Botão para colapso no mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Links de menu -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Sobre Nós</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('valores') ? 'active' : '' }}" href="/valores">Nossos Valores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('comprar-refeicao') ? 'active' : '' }}" href="/comprar-refeicao">Comprar Refeição</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contato') ? 'active' : '' }}" href="/contato">Contacto</a>
                </li>

                <!-- Botão para acionar o sidebar do carrinho -->
                <li class="nav-item position-relative">
                    <a class="nav-link" href="/carrinho"
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#offcanvasCart" 
                        aria-controls="offcanvasCart">
                        <i class="bi bi-cart"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="cart-badge">{{ count(session('cart')) }}</span>
                            <x-cart/>
                        @endif
                    </a>
                </li>

                <!-- Ícone de login -->
                @auth
                    <li class="nav-item">
                        <x-user-dropdown :user="auth()->user()" />  
                    </li>
                @else
                    <li class="nav-item">
                        <x-profile-icon />
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" ></script>

<style>
    .navbar-brand img {
        max-height: 60px;
    }
    
    .nav-link {
        font-size: 18px;
        margin-right: 15px;
        text-decoration: none;
    }
    
    .nav-link:hover {
        color: #F25F29;
    }
    
    .nav-link.active {
        color: #F25F29 !important;
        font-weight: bold;
    }
    
    .bi {
        font-size: 24px;
    }

    /* Estilo para o badge do carrinho */
    .nav-item.position-relative {
        position: relative;
    }

    .cart-badge {
        position: absolute;
        top: -5px; /* Ajuste a posição vertical */
        right: -10px; /* Ajuste a posição horizontal */
        background-color: #F25F29;
        color: white;
        border-radius: 50%;
        padding: 2px 6px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        /* Remove o transform ou ajuste conforme necessário */
        transform: none; 
    }

    /* Opcional: Ajuste para telas menores */
    @media (max-width: 576px) {
        .cart-badge {
            top: -8px;
            right: -12px;
            font-size: 10px;
            min-width: 18px;
            height: 18px;
        }
    }
</style>
