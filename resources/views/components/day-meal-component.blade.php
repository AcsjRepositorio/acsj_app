@props(['mealOfTheDay' => null])

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 day-meal-card">
            @if(!empty($mealOfTheDay))
                <div class="row bg-white overflow">
                    <!-- Imagem da refeição -->
                    <div class="col-lg-5 col-md-6 d-flex justify-content-center align-items-center p-3">
                        <div class="position-relative w-100 h-100">
                            <div class="days-meal-image" style='background-image: url("{{ asset('images/retangleyellow.png')}}")>
                                <div class="menu-label text-center fw-bold text-white fs-4">Menu do dia</div>
                                <img src="{{ $mealOfTheDay->photo && file_exists(public_path('storage/' . $mealOfTheDay->photo)) 
                                        ? asset('storage/' . $mealOfTheDay->photo) 
                                        : asset('images/default-meal.jpg') }}"
                                    alt="Foto de {{ $mealOfTheDay->name }}" class="meal-photo img-fluid rounded">
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo da refeição -->
                    <div class="col-lg-7 col-md-6 d-flex flex-column justify-content-end p-4">
                        <div class="meal-details mb-3">
                            <h2 class="meal-title fw-bold fs-3">{{ $mealOfTheDay->name }}</h2>
                            <p class="meal-description text-muted">
                                {{ Str::limit($mealOfTheDay->description, 200, '...') }}
                            </p>
                            <p class="meal-price text-danger fs-4 fw-bold">€{{ number_format($mealOfTheDay->price, 2) }}</p>
                        </div>
                        <form method="POST" action="{{ route('cart.store') }}" class="mt-0">
                            @csrf
                            <input type="hidden" name="meal_id" value="{{ $mealOfTheDay->id }}">
                            <div class="d-flex flex-column flex-md-row">
                                <button type="submit" class="btn custom-btn me-3 mb-2 mb-md-0">Adicionar ao carrinho</button>
                                <a href="/menu-semanal" class="menu-link text-decoration-none">Menu completo da semana</a>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            {{-- Se futuramente desejar exibir o placeholder, descomente o bloco abaixo --}}
            {{--
            @else
                <div class="day-meal-placeholder text-center">
                    <img src="{{ asset('images/placeholder-special.jpg') }}" alt="Preparando a refeição" class="img-fluid">
                    <p class="mt-4 fs-4 text-muted">Nossos chefes estão preparando uma comida especial para esta semana.</p>
                </div>
            @endif
            --}}
        </div>
    </div>
</div>

<style>
    
    .day-meal-card {
        max-width: 100%;
    }

    .days-meal-image {
        width: 100%;
        height: 100%;
        min-height: 400px;
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        border-radius: 12px;
        position: relative;
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

    .custom-btn {
        background-color: #F25F29;
        border-color: #F25F29;
        color: white;
    }

    .custom-btn:hover {
        background-color: #d84e20;
        border-color: #d84e20;
    }

    .meal-details {
        margin-bottom: 8px;
    }

    form.mt-0 {
        margin-top: 0;
    }

    .col-lg-7 {
        justify-content: flex-start;
    }

    .col-lg-5,
    .col-lg-7 {
        padding: 0.5rem;
    }

    .day-meal-placeholder {
        padding: 20px;
    }

    .day-meal-placeholder img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }
</style>

