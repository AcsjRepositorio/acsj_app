@props(['meal'])

<div class="card">
    <div class="price-badge">€{{$meal->price}}</div>
    <div class="image-wrapper">
        <img src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                ? asset('storage/' . $meal->photo) 
                : asset('images/default-meal.jpg') }}"
            alt="Foto de {{ $meal->name }}">
        <div class="day-badge">
            {{ ucfirst($meal->day_of_week) }}
        </div>
    </div>

    <h3 class="card-title">{{$meal->name}}</h3>
    <p class="card-description">
        {{$meal->description}}
    </p>

    <div class="d-flex justify-content-between align-items-center">
        <a type="button" class="button">Adicionar ao carrinho</a>

        <button
    type="button"
    class="btn btn-outline-secondary"
    data-bs-toggle="modal"
    data-bs-target="#mealModal"
    data-meal-name="{{ $meal->name }}"
    data-meal-photo="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) ? asset('storage/' . $meal->photo) : asset('images/default-meal.jpg') }}"
    data-meal-description="{{ $meal->description }}"
    data-meal-price="{{ $meal->price }}"
    data-meal-day="{{ ucfirst($meal->day_of_week) }}">
    Ver mais
</button>


    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="mealModal-{{ $meal->id }}" tabindex="-1" aria-labelledby="mealModalLabel-{{ $meal->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mealModalLabel-{{ $meal->id }}">{{ $meal->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img
                    src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                            ? asset('storage/' . $meal->photo) 
                            : asset('images/default-meal.jpg') }}"
                    alt="Foto de {{ $meal->name }}"
                    class="img-fluid rounded-lg mb-3">
                <p class="text-gray-700">{{ $meal->description }}</p>
                <p class="text-green-600 font-bold">Preço: €{{ $meal->price }}</p>
                <p>Disponível: {{ ucfirst($meal->day_of_week) }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        width: 300px;
        border-radius: 15px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
        padding: 16px;
        text-align: center;
    }

    .image-wrapper {
        position: relative;
        width: 100%;
        border-radius: 15px;
        overflow: hidden;
    }

    .image-wrapper img {
        width: 100%;
        display: block;
        border-radius: 15px;
    }

    .day-badge {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        padding: 8px 0;
        font-size: 14px;
        text-align: center;
        border-bottom-left-radius: 15px;
        border-bottom-right-radius: 15px;
    }

    .price-badge {
        position: absolute;
        top: -4.5px;
        right: -4px;
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

    .card-title {
        font-size: 18px;
        font-weight: bold;
        margin: 8px 0;
    }

    .card-description {
        font-size: 14px;
        color: #6c757d;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
    }

    a {
        text-decoration: none;
    }

    .button {
        border-radius: 8px;
        padding: 6px;
        background-color: #517AF0;
        color: #fff;
        border: none;
        text-decoration: none;
    }

    .button:hover {
        background-color: #6C757D;
    }

    /* Garantir que o modal funcione corretamente */
    .modal-dialog {
        max-width: 800px;
        margin: 1.75rem auto;
    }

    .modal-content {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .btn-close {
        all: unset;
        cursor: pointer;
        background-color: transparent;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-close:hover {
        background-color: #f0f0f0;
        border-radius: 50%;
    }
</style>
