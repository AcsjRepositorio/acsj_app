
  
 


@include('components.modal.aboutUs')
@include('components.modal.ourValues')

  <body id="top">

<footer class="bg-light py-4 mt-5">
    <div class="container">
        <div class="row">

            <div class=" offset-col-2 col-12 col-md-12 mb-4 mb-md-0">
                <div class="col-md-12 mb-3 d-flex justify-content-start">
                    <a class="navbar-brand" href="/">
                        <img src="{{ asset('images/acsj_logo.png') }}" alt="Logo" style="height: 60px;">
                    </a>
                    <div class="col-md-11 ms-3">
                        <h2>Associação das crianças</h2>
                        <h2>do São João</h2>
                    </div>
                </div> 
            </div>

            <div class="d-flex">
                <div class="col-6 col-md-10">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                        
                            <a href="#" class="text-dark text-decoration-none" data-bs-toggle="modal" data-bs-target="#sobreNosModal" >Sobre nós</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-dark text-decoration-none " data-bs-toggle="modal" data-bs-target="#nossosValoresModal" >Nossos valores</a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-dark text-decoration-none">Comprar refeições</a>
                        </li>

                    </ul>
                </div>

                <div class="col-md-2">
                    <address class="text-dark mb-3">
                    Alameda Professor Hernâni Monteiro<br>
                    4200-319 , Porto
                    </address>
                    
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="mb-0"> &copy; ASCJ -  2025 </p>
            </div>
        </div>
    </div>
</footer>

<!-- Botão de Voltar ao Topo -->
<a href="#top" class="scroll-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script para ativar o botão de rolagem suave -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollButton = document.querySelector(".scroll-to-top");

        window.addEventListener("scroll", function () {
            if (window.scrollY > 200) {
                scrollButton.style.display = "flex";
            } else {
                scrollButton.style.display = "none";
            }
        });

        scrollButton.addEventListener("click", function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });
</script>

<style>
    a {
        text-decoration: none !important;

    }

    ul.list-unstyled li a {
    color: #333 !important; /* Garante que a cor padrão seja aplicada */
    transition: color 0.3s ease-in-out;
}

ul.list-unstyled li a:hover {
    color: #ff6d00 !important; /* Garante que a cor mude no hover */
}


    

    /* Estilizando o botão de voltar ao topo */
    .scroll-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #ff6d00;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: background-color 0.3s ease, opacity 0.3s ease;
    }

    .scroll-to-top:hover {
        background-color: #ff9e3b;
    }
</style>

<!-- Adiciona Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
