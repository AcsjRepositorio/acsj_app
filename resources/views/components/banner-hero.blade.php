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
                  <!-- Título principal sobre a imagem com responsividade via clamp() -->
                  <h1 class="position-absolute w-100 text-end main-text mb-3" 
                      style="top: 36%; left: 35%; transform: translate(-50%, -50%);
                             font-size: clamp(1.4rem, 2.5vw, 2.5rem);">
                      Planeie sua semana sem filas
                  </h1>

                  <!-- Sub-text modificado para quebrar em duas linhas no modo responsivo -->
                  <h3 class="position-absolute text-center sub-text" 
                      style="top: 47%; left: 62%; transform: translate(-50%, -50%);
                             font-size: clamp(1.1rem, 2vw, 1.8rem); max-width: 35ch; word-wrap: break-word;">
                      Faça seu pedido online 
                      <span class="line-break">e aproveite suas refeições sem espera!</span>
                  </h3>
              </div>

              <!-- Botão com tamanhos responsivos -->
              <div class="position-absolute w-100 text-end" 
                  style="top: 65%; left: 30%; transform: translate(-50%, -50%);">
                  <a href="#scroll-day-meal" class="btn btn-lg btn-responsive" 
                     style="font-size: clamp(0.9rem, 2vw, 1.2rem); padding: clamp(8px, 2vw, 16px) clamp(16px, 3vw, 24px);">
                      Iniciar pedido
                  </a>
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
  
  /* Estilização responsiva padrão */
  .btn {
    font-size: 0.9rem;
    padding: 8px 16px;
  }

  /* Define que, por padrão, o span não quebra a linha */
  .line-break {
    display: inline;
  }

  /* Para telas médias */
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
          /* Margens proporcionais */
          margin-top: clamp(10px, 5vw, 20px);
          margin-bottom: clamp(10px, 5vw, 20px);
      }
      .btn{
        margin-top: clamp(20px, 5vw, 36px);
      }
  }

  /* Para telas pequenas */
  @media (max-width: 600px) {
      .main-text {
          top: 30% !important;
          left: 45% !important;
          margin-bottom: 16px;
      }
      .sub-text {
          top: 40% !important;
          left: 60% !important;
          /* Quebra o texto em duas linhas */
          text-align: center;
          margin-bottom: 16px;
          margin-top: 16px;
      }
      /* Força o span a exibir em bloco, quebrando a linha */
      .sub-text .line-break {
          display: block;
      }
      .btn {
          top: 55% !important;
          left: 45% !important;
          margin-top: clamp(16px, 5vw, 36px);
      }
  }

  /* Para telas ainda menores */
  @media (max-width: 576px) {
      .main-text {
          font-size: 1.4rem;
          width: 80%;
          left: 50%;
      }
      .sub-text {
          font-size: 1.1rem;
          width: 70%;
          right: 30%;
      }
      .btn{
        margin-top: clamp(16px, 5vw, 36px);
      }
  }
</style>
