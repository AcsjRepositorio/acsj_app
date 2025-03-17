<!-- Título principal acima da imagem -->
<div class="container text-center text-brand-color">
  <h1>Bar da Associação</h1>
  <h1>das Crianças do São João</h1>
</div>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-11 position-relative">
      <picture>
        <source media="(max-width: 600px)" srcset="{{ asset('images/hero-imagemobile.svg') }}">
        <img 
          src="{{ asset('images/hero-image.png') }}" 
          alt="Imagem de refeições" 
          class="img-fluid w-100"
        >
      </picture>

      <!-- Texto e botão dentro de uma div -->
      <div>
        <div class="text-white">
          <!-- Sub-texto com quebra de linha opcional -->
          <div class="position-absolute sub-text">
            <h3>Planeie sua semana sem filas</h3>
            <h4 class="line-break">e aproveite suas refeições sem espera!</h4>

            <a href="#scroll-day-meal" class="btn btn-responsive">
              Iniciar pedido
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Cor de destaque para o texto principal */
  .text-brand-color {
    color: #FF452B;
  }

  /* Cor branca para textos sobre a imagem */
  .text-white {
    color: #fff;
  }

  /* Título principal sobre a imagem */
  .main-text {
    top: 36%;
    left: 35%;
    transform: translate(-50%, -50%);
    font-size: clamp(1.4rem, 2.5vw, 2.5rem);
    width: 100%;
    text-align: end;
    margin-bottom: 3rem;
    background-color: rgba(0, 0, 0, 0.6);
    padding: 0.5rem 1rem;
    border-radius: 8px;
  }

  /* Subtítulo (h3) sobre a imagem */
  .sub-text {
    top: 50%;
    left: 62%;
    transform: translate(-50%, -50%);
    font-size: clamp(1.0rem, 2vw, 1.5rem);
    max-width: 35ch;
    word-wrap: break-word;
    text-align: center;
    background-color: rgba(0, 0, 0, 0.55);
    padding: 16px;
    border-radius: 8px;
  }

  /* Container do botão */
  .button-container {
    top: 65%;
    left: 30%;
    transform: translate(-50%, -50%);
    width: 100%;
    text-align: end;
  }

  /* Botão padrão */
  .btn {
    background-color: #FF452B;
    color: #fff !important;
    font-weight: bold;
    font-size: clamp(0.9rem, 2vw, 1rem);
  }

  .btn:hover {
    background-color: #F25F29;
  }

  /* Define que, por padrão, o span não quebra a linha */
  .line-break {
    display: inline;
  }

  /* Responsividade para telas médias (até 768px) */
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
      margin-top: clamp(10px, 5vw, 20px);
      margin-bottom: clamp(10px, 5vw, 20px);
    }
    .btn {
      margin-top: clamp(20px, 5vw, 36px);
    }
  }

  /* Responsividade para telas pequenas (até 600px) */
  @media (max-width: 600px) {
    .main-text {
      top: 30% !important;
      left: 42% !important;
      margin-bottom: 36px;
    }
    .sub-text { 
      top: 42% !important;
      left: 50% !important;
    }
    .sub-text .line-break {
      display: block;
    }
    .button-container {
      top: 63% !important;
    }
    .img-fluid {
      opacity: 0.8; /* Mais escuro para melhor contraste com o texto */
    }
  }

  /* Responsividade para telas muito pequenas (até 576px) */
  @media (max-width: 576px) {
    .main-text {
      font-size: 1.4rem;
      width: 80%;
      left: 50%;
    }
    .sub-text {
      font-size: 1.1rem;
      width: 70%;
      right: 40%;
    }
    .btn {
      margin-top: clamp(16px, 5vw, 16px);
    }
  }
</style>
