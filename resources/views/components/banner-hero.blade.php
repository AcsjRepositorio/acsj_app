
    <!-- Título principal acima da imagem -->
    <div class="container text-center my-4">
        <h1>Bar da associação das crianças do São João</h1>
    </div>

    <!-- Hero Section -->
    <div class="container mb-5">
        <div class="row justify-content-center">
            <!-- Coluna centralizada em desktop (8 colunas) -->
            <div class="col-12 col-md-11 position-relative">
                <!-- Imagem que será o "fundo" do texto -->
                <img 
                    src="{{ asset('images/hero-image.png') }}" 
                    alt="Imagem de refeições" 
                    class="img-fluid w-100"
                >

                <!-- Texto e botão em posição absoluta sobre a imagem -->
                <div 
                    class=" background-text position-absolute top-50 translate-middle text-center p-3 col-12 col-md-4" 
                    
                >
                    <h2 class="mb-3">Planei sua semana sem filas</h2>
                    <p class="mb-4">
                        Faça seu pedido online, garanta sua senha e aproveite 
                        as suas refeições sem espera!
                    </p>
                    <a href="#" class="btn btn-primary btn-lg">
                        Iniciar pedido
                    </a>
                </div>
            </div>
        </div>
    </div>


    <style>

.background-text{
  top: 50%;
  left: 65%;
  height: 50%;
  color: #fff;
  width:40%;

  .h2{

    width: 100%;
    font-size: large;
    

  }

} 
    </style>
   

      
