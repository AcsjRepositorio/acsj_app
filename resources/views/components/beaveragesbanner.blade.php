@include('beverages')


<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-11 position-relative">
      <img 
        src="{{ asset('images/bannerbebida.png') }}" 
        alt="Imagem de refeições" 
        class="img-fluid w-100"
      >
      <div class="position-absolute w-100" style="top: 50%; left: 70%; transform: translate(-50%, -50%);">
        <div class="d-flex flex-column align-items-center">
          <h1 class="titulo text-white text-center">Adicione uma bebida</h1>
          <a href="#" class="btn btn-primary btn-menu mt-3" data-bs-toggle="modal" data-bs-target="#beverages" style="border: none;">Ver menu</a>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Ajustes para modo mobile -->

<style>
 .titulo {
  font-size: clamp( 2rem, 2vw, 3rem);
}

/* Ajuste adicional para telas menores */
@media (max-width: 600px) {
  .titulo {
    font-size:  1rem;
  }
}
</style>




    





