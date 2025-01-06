<div class="day-meal-card">
    @if($mealOfTheDay)
        <div class="days-meal-container">
            <div class="days-meal-image" style="background-image: url('{{ asset('images/retangleyellow.png') }}');">
                <img src="{{ $mealOfTheDay->photo && file_exists(public_path('storage/' . $mealOfTheDay->photo)) 
                        ? asset('storage/' . $mealOfTheDay->photo) 
                        : asset('images/default-meal.jpg') }}" 
                    alt="Foto de {{ $mealOfTheDay->name }}" class="meal-photo">
            </div>
            <div class="days-meal-content">
                <h2 class="meal-title">{{ $mealOfTheDay->name }}</h2>
                <p class="meal-description">{{ $mealOfTheDay->description }}</p>
                <p class="meal-price">Preço: €{{ $mealOfTheDay->price }}</p>
                <form method="POST" action="{{ route('cart.store') }}">
                    @csrf
                    <input type="hidden" name="meal_id" value="{{ $mealOfTheDay->id }}">
                    <button type="submit" class="button">Inicie seu pedido</button>
                </form>
            </div>
        </div>
    @else
        <p class="alert alert-info">Nenhuma refeição está definida como "prato do dia" para hoje.</p>
    @endif
</div>

<style>
    .day-meal-card {
        margin-bottom: 20px;
    }

    .days-meal-container {
        display: flex;
        align-items: center;
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .days-meal-image {
        width: 40%;
        background-repeat: no-repeat;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .meal-photo {
        width: 100%;
        max-height: 200px;
        border-radius: 8px;
        object-fit: cover;
    }

    .days-meal-content {
        padding: 16px;
        width: 60%;
    }

    .meal-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .meal-description {
        font-size: 16px;
        color: #555;
        margin-bottom: 12px;
    }

    .meal-price {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 16px;
    }

    .button {
        border-radius: 8px;
        padding: 8px 16px;
        background-color: #F04F30;
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        border: none;
        cursor: pointer;
    }

    .button:hover {
        background-color: #d14227;
    }
</style>
