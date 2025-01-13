<div class="container my-5">
  <!-- Início da linha -->
  <div class="row d-flex align-items-center">
    
    <!-- Coluna Imagem -->
    <div class="col-12 col-md-6 mb-3    ">
      <div class="d-flex justify-content-center align-items-center p-3">
        <div class="position-relative w-100 h-100">
          <div class="days-meal-image" style="background-image: url('{{ asset('images/retangleyellow.png') }}')">
            <span class="menu-label text-center fw-bold text-white fs-4">Menu do dia</span>
            <img
              src="{{ asset('images/pratodefault.png') }}"
              alt="imagem de prato padrão"
              class="meal-photo img-fluid rounded"
            >
          </div>
        </div>
      </div>
    </div>
    
    <!-- Coluna Texto -->
    <div class="col-12 col-md-6 mb-3 d-flex flex-column justify-content-center">
      <h1 class="text-center">Bar da Asociação das Crianças do São João</h1>
      <h5>Planeie sua semana sem filas</h5>
      <p>
      Faça seu pedido online, garanta sua senha e aproveite suas refeições sem espera. Escolhar
      seus pratos favoritos para semana e aproveite mais do dia
      </p>
      <p class="lead mt-5">
      <button type="submit" class="button add-to-cart">Iniciar pedido</button>
      </p>
    </div>
    
  </div>
  
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        h1, h3, h3 {
            font-family: 'Nunito', sans-serif;
        }

        .menu-label {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 20px;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .meal-photo {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            max-width: 250px;
            transform: translate(-50%, -50%);
            border-radius: 8px;
        }

        .days-meal-image {
            width: 100%;
            height: 100%;
            /* Atualizado para ocupar 100% da altura do elemento pai */
            min-height: 400px;
            /* Altura mínima para manter consistência */
            background-repeat: no-repeat;
            background-size: contain;
            /* Ajusta para caber completamente na vertical */
            background-position: center;
            border-radius: 12px;
            position: relative;
        }


        .button {
            border-radius: 8px;
            padding: 8px 16px;
            background-color: #517AF0;
            color: #fff;
            border: none;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>