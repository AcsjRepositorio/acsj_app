@props(['meal'])

@php
    use Carbon\Carbon;
    $hoje = Carbon::today();

    // Se a refeição for do tipo Almoço (category_id == 2) e possuir data definida,
    // verifica se a data de venda já passou.
    if ($meal->category_id == 2 && $meal->day_week_start) {
        $menuExpirado = Carbon::parse($meal->day_week_start)->lt($hoje);
    } else {
        $menuExpirado = false;
    }

    // Recupera o carrinho da sessão e obtém a quantidade já adicionada para este produto.
    $cart = session('cart', []);
    $inCart = isset($cart[$meal->id]) ? $cart[$meal->id]['quantity'] : 0;

    // Define a condição para desabilitar o botão:
    // - Se for Almoço e o menu estiver expirado,
    // - Ou se o estoque for 0 ou menor,
    // - Ou se a quantidade já no carrinho for igual ou superior ao estoque disponível.
    $disableButton = (($meal->category_id == 2 && $menuExpirado) || $meal->stock <= 0 || ($inCart >= $meal->stock));
@endphp

<div class="card {{ $disableButton ? 'expired' : '' }}">
    <div class="price-badge">€{{ $meal->price }}</div>
    <div class="image-wrapper">
        <img src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                ? asset('storage/' . $meal->photo) 
                : asset('images/default-meal.jpg') }}"
             alt="Foto de {{ $meal->name }}">

        @if((($meal->category_id == 2 && $menuExpirado) || $meal->stock <= 0))
            <div class="sold-out-badge">Menu esgotado</div>
        @endif

        @if($meal->category_id == 2 && $meal->day_week_start)
            <!-- Badge de data sempre fixada na parte inferior da imagem -->
            <p class="day-badge">
                {{ Carbon::parse($meal->day_week_start)->format('d-m-Y') }} - {{ ucfirst($meal->day_of_week) }}
            </p>
        @endif
    </div>

    <!-- Conteúdo do card -->
    <div class="card-content">
        <h3 class="card-title">{{ $meal->name }}</h3>
        <p class="card-description">{{ $meal->description }}</p>
    </div>

    <!-- Rodapé fixo para o botão -->
    <div class="card-footer d-flex flex-column gap-2">
        <!-- Botão Adicionar ao Carrinho -->
        <form method="POST" action="{{ route('cart.store') }}">
            @csrf
            <input type="hidden" name="meal_id" value="{{ $meal->id }}">
            <button type="submit" class="button add-to-cart w-100" {{ $disableButton ? 'disabled' : '' }}>
                Adicionar ao carrinho
            </button>
        </form>

        <!-- Botão "Mais detalhes" que abre o modal -->
        <button 
            type="button" 
            class="button w-100" 
            {{ $disableButton ? 'disabled' : '' }}
            data-bs-toggle="modal"
            data-bs-target="#mealModal"
            data-meal-name="{{ $meal->name }}"
            data-meal-description="{{ $meal->description }}"
            data-meal-photo="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                                ? asset('storage/' . $meal->photo) 
                                : asset('images/default-meal.jpg') }}"
            data-meal-price="{{ $meal->price }}"
        >
            Mais detalhes
        </button>
    </div>
</div>

<style>
  /* Define o card com layout flexível e divide em três seções */
  .card {
    display: flex;
    flex-direction: column;
    width: 300px;
    height: 450px; /* Tamanho fixo para todos os cards */
    border-radius: 15px;
    background-color: #fff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
    padding: 16px;
    text-align: center;
    transition: 0.3s;
  }

  .image-wrapper {
    position: relative;
    width: 100%;
    height: 180px; /* Define altura fixa para garantir consistência */
    border-radius: 15px;
    overflow: hidden;
  }

  .image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Garante que a imagem preencha a área corretamente */
    border-radius: 15px;
  }

  /* Badge de data */
  .day-badge {
    position: absolute;
    bottom: -15px;
    left: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.6);
    color: #fff;
    padding: 8px 0;
    font-size: 14px;
    text-align: center;
    font-weight: bold;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    z-index: 10;
  }

  /* Badge "Menu Esgotado" */
  .sold-out-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    background: #F2D338;
    color: #000;
    padding: 8px 14px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 5px;
    transform: rotate(15deg);
    z-index: 1000;
  }

  /* Card com aparência alterada quando expirado ou sem estoque */
  .card.expired {
    background-color: rgba(187, 201, 59, 0.65);
    opacity: 0.6;
  }

  /* Badge de preço */
  .price-badge {
    position: absolute;
    top: 4.5px;
    right: 4px;
    background-color: #00C49A;
    color: #fff;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 18px;
    font-weight: bold;
    z-index: 1;
  }

  /* Conteúdo do card */
  .card-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }

  .card-title {
    font-size: 18px;
    font-weight: bold;
    margin: 8px 0;
  }

  /* Descrição truncada */
  .card-description {
    font-size: 14px;
    color: #6c757d;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4; /* Limita a 4 linhas */
    -webkit-box-orient: vertical;
    white-space: normal;
    margin-bottom: 10px;
  }

  /* Rodapé do card */
  .card-footer {
    margin-top: auto;
    padding-top: 10px;
  }

  /* Botão */
  .button {
    border-radius: 8px;
    padding: 10px;
    background-color: #517AF0;
    color: #fff;
    border: none;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
  }

  .button:hover {
    background-color: #415bb5;
  }

  .button[disabled] {
    background-color: #ccc;
    cursor: not-allowed;
  }
</style>

