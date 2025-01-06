














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
                    <a class="nav-link {{ request()->is('contato') ? 'active' : '' }}" href="/contato">Contato</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="/carrinho">
                        <i class="bi bi-cart"></i>
                    </a>
                </li>
                <!-- Ícone de login -->

                @auth
                <li class="nav-item">
                    
                    <x-user-dropdown :user="auth()->user()" />
                


                    @else

                  
                    <x-profile-icon />
                  

                </li>

              @endauth

                

                
              
            
            </ul>
        </div>
    </div>
</nav>


<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


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

</style>




