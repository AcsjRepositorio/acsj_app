@include('components.modal.aboutUs')
@include('components.modal.ourValues')
@include('components.modal.contact')

<!-- Cabeçalho para Desktop -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-none d-lg-flex">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/acsj_logo.png') }}" alt="Logo" style="height: 200px;">
        </a>
        <!-- Itens de Navegação e Ícones -->
        <ul class="navbar-nav ms-auto flex-row align-items-center">
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('/') ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#sobreNosModal">
                    Sobre nós
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('valores') ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#nossosValoresModal">
                    Nossos valores
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link {{ request()->is('valores') ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#contact">
                    Contactos
                </a>
            </li>
            <li class="nav-item position-relative cart-container">
                <a class="nav-link" href="/carrinho" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                    <i class="bi bi-cart"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                        <p class="cart-badge">{{ count(session('cart')) }}</p>
                    @endif
                </a>
            </li>
            @auth
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth()->user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('password.change') }}">
                            <i class="bi bi-person"></i>
                            Atualizar senha
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('minhas.encomendas') }}">
                            <i class="bi bi-person"></i>
                            Minhas encomendas
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex align-items-center">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                    @if(auth()->user()->user_type === \App\Models\User::TYPE_ADMIN)
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="/adminpanel/manage_order">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @else
            <li class="nav-item">
                <x-profile-icon/>
            </li>
            @endauth 
        </ul>
    </div>
</nav>

<!-- Cabeçalho para Mobile -->
<nav class="navbar navbar-light bg-white shadow-sm d-lg-none">
    <div class="container-fluid flex-column">
        <!-- Primeira Linha: Logo e Ícones -->
        <div class="d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/acsj_logo.png') }}" alt="Logo" style="height: 200px;">
            </a>
            <ul class="navbar-nav d-flex flex-row align-items-center mb-0">

            @auth
                <li class="nav-item dropdown" >
                    <!-- Adicionado data-bs-display="static" para evitar reflow -->
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMobile" role="button"
                       data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMobile">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('password.change') }}">
                                <i class="bi bi-person"></i>
                                Atualizar senha
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Logout
                                </button>
                            </form>
                        </li>
                        @if(auth()->user()->user_type === \App\Models\User::TYPE_ADMIN)
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="/adminpanel/manage_order">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <x-profile-icon />
                </li>
                @endauth


                <li class="nav-item position-relative cart-container ">
                    <a class="nav-link" href="/carrinho" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                        <i class="bi bi-cart"></i>
                        @if(session('cart') && count(session('cart')) > 0)
                            <p class="cart-badge">{{ count(session('cart')) }}</p>
                        @endif
                    </a>
                </li>
               
            </ul>
        </div>
        <!-- Segunda Linha: Links de Navegação Centralizados -->
        <div class="w-100 mt-2">
            <ul class="navbar-nav justify-content-center flex-row">
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('/') ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#sobreNosModal">
                        Sobre nós
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('valores') ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#nossosValoresModal">
                        Nossos valores
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ request()->is('valores') ? 'active' : '' }}" data-bs-toggle="modal" data-bs-target="#contact">
                        Contactos
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap e Bootstrap Icons -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script> 

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
      crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
        position: relative;
    }

    /* Estilo para o badge do carrinho */
    .cart-container {
        position: relative;
    }

    .cart-badge {
        border-radius: 50%;
        background-color: #F25F29;
        height: 24px;
        width: 24px;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        top: -15px;
        left: 15px;
    }
</style>
