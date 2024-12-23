@extends('layouts.masterlayout') 

@section('content')
    @auth
        <x-user-dropdown :user="auth()->user()" />
    @else
        <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
        @endif
    @endauth

    <h1>Home Page</h1>
    <div class="meal-cards">
        @foreach ($meals as $meal)
            <x-cards.meal-week :meal="$meal" />
        @endforeach
    </div>

    <!-- Modal Dinâmico -->
    <div class="modal fade" id="mealModal" tabindex="-1" aria-labelledby="mealModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mealModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" id="mealModalPhoto" alt="Foto da refeição" class="img-fluid rounded-lg mb-3">
                    <p id="mealModalDescription" class="text-gray-700"></p>
                    <p id="mealModalPrice" class="text-green-600 font-bold"></p>
                    <p id="mealModalDay"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mealModal = document.getElementById('mealModal');

            mealModal.addEventListener('show.bs.modal', (event) => {
                const button = event.relatedTarget;
                const mealName = button.getAttribute('data-meal-name');
                const mealPhoto = button.getAttribute('data-meal-photo');
                const mealDescription = button.getAttribute('data-meal-description');
                const mealPrice = button.getAttribute('data-meal-price');
                const mealDay = button.getAttribute('data-meal-day');

                document.getElementById('mealModalLabel').textContent = mealName;
                document.getElementById('mealModalPhoto').setAttribute('src', mealPhoto);
                document.getElementById('mealModalDescription').textContent = mealDescription;
                document.getElementById('mealModalPrice').textContent = `Preço: €${mealPrice}`;
                document.getElementById('mealModalDay').textContent = `Disponível: ${mealDay}`;
            });
        });
    </script>
@endsection

