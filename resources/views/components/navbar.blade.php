<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/acsj_logo.png') }}" alt="Logo" style="height: 60px;">
        </a>

        <!-- Botão para colapso no mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                <li class="nav-item position-relative cart-container">
                    <a class="nav-link" href="/carrinho"
                       data-bs-toggle="offcanvas" 
                       data-bs-target="#offcanvasCart" 
                       aria-controls="offcanvasCart">
                        
                            <i class="bi bi-cart"></i>
                            @if(session('cart') && count(session('cart')) > 0)
                                <p class="cart-badge">{{ count(session('cart')) }}</p>
                            @endif
                        
                    </a>
                   
                </li>

                <!-- Ícone de login / dropdown do usuário -->
                @auth
                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
       data-bs-toggle="dropdown" aria-expanded="false">
        {{ auth()->user()->name }}
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
        <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.edit') }}">
            <i class="bi bi-person"></i>
                Editar Perfil
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
        <li>
            <a class="dropdown-item d-flex align-items-center" href="/dashboard">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>
    </ul>
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


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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



.cart-badge{
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

@media (max-width: 576px) {
    .cart-container{
       
        margin-top: 16px;
    }

}    

</style>
