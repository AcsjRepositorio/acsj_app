<div class="d-flex justify-content-center align-items-center flex-column">

<div class="header-titles" style="color: #F25F29">
  <h1>Bar da Associação</h1>
  <h1>das crianças do São João</h1>
</div>

  <div class="hero-bg">
    <div class="hero-text rounded">
      <h1 class="mb-2">Planei sua semana sem filas</h1>
      <h1 class="mb-5 text-center">e aproveite suas refeições sem espera!</h1>
      <a  href="#scroll-day-meal" class="btn btn-responsive mb-3 ">Iniciar pedido</a>
    </div>
  </div>
</div>

<link rel="preload" as="image" href="/images/hero-image.png">
<link rel="preload" as="image" media="(max-width: 768px)" href="/images/hero-imagemobile.svg">

<style>

.header-titles {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* Imagem de fundo para telas maiores (desktop) */


  .hero-bg {
    background-image: url("/images/hero-image.png");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    width: 100%;
    height: 100vh; /* Ajuste a altura conforme necessário */
    display: flex;              /* Permite centralizar o conteúdo */
    justify-content: center;    /* Centraliza horizontalmente */
    align-items: center;        /* Centraliza verticalmente */
    
  }

  .hero-text {
    display: inline-flex;        /* A div se ajusta ao tamanho do conteúdo */
    flex-direction: column;      /* Organiza os elementos verticalmente */
    align-items: center;         /* Centraliza os <h3> */
    background-color: rgba(12, 11, 11, 0.66);
    padding: 12px;
    border-radius: 5px;
    color: #ffff;
  }

 

  .btn {
    background-color: #FF452B;
    color: #fff !important;
    font-weight: bold;
    font-size: clamp(0.9rem, 2vw, 1rem);
  }

  .btn:hover{
    background-color:rgba(255, 68, 43, 0.79)
  }
  
  /* Imagem de fundo para telas menores (tablet e dispositivos móveis) */
  @media (max-width: 768px) {
    .hero-bg {
      background-image: url("/images/hero-imagemobile.svg");
    }


    @media (max-width: 768px) {
    .header-titles {
      flex-direction: column;
    }
    .header-titles h1 {
      margin: 5px 0;
      text-align: center;
    }
  }
  }
</style>


