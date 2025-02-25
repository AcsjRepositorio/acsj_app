  <!-- Título principal acima da imagem -->
  <div style="color: #FF452B;" class="container text-center my-4">
      <h1>Bar da Associação </h1>
      <h1>das Crianças do São João</h1>
  </div>

  <div class="container mb-5">
      <div class="row justify-content-center">
          <div class="col-12 col-md-11 position-relative">
              <img 
                  src="{{ asset('images/hero-image.png') }}" 
                  alt="Imagem de refeições" 
                  class="img-fluid w-100"
              >

              <!-- Texto e botão dentro de uma div -->

              <div>

              <div style="color: #fff;">

              <h1 class=" position-absolute w-100 text-end main-text " style="top: 36%; left: 38%; transform: translate(-50%, -50%);">Planei sua semana sem filas</h1>

              <h3 class=" position-absolute w-100 text-end mb-3 sub-text" style="top: 47%; left: 30%; transform: translate(-50%, -50%);"> 
                Faça seu pedido online e 
              </h3>

              <h3 class=" position-absolute w-100 text-end mt-3 mb-3 sub-text" style="top: 52%; left: 38%; transform: translate(-50%, -50%);"> 
              aproveite suas refeições sem espera!
              </h3>


              </div>


              
              <div class="position-absolute w-100 text-end mt-3" style="top: 65%; left: 23%; transform: translate(-50%, -50%);">
              
              <a href="#scroll-day-meal" class="btn btn-lg btn-responsive">Iniciar pedido</a>
              </div>


              </div>


          </div>
      </div>
  </div>

  <style>
    .btn {
      background-color: #FF452B;
      color: #fff !important;
      font-weight: bold;
    }

    .btn:hover{
      background-color: #F25F29;
      
    }


    /* Modo responsivo */
    .btn {
      font-size: 0.9rem;
      padding: 8px 16px;
    }


    @media (max-width: 768px) {
        .main-text {
            font-size: 1.7rem;
            width: 70%;
            left: 50%;
        }

        .sub-text {
            font-size: 1.3rem;
            width: 60%;
            left: 50%;
        }

        .btn{
          margin-top: 36px;
        }
    }

    @media (max-width: 576px) {
        .main-text {
            font-size: 1.4rem;
            width: 80%;
            left: 50%;
        }

        .sub-text {
            font-size: 1.1rem;
            width: 70%;
            left: 50%;
        }

        .btn{
          margin-top: 36px;
        }
    }
  </style>

