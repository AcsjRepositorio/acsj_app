@props(['meal'])


<div class="card">
    <div class="price-badge">€{{ $meal->price }}</div>
    <div class="image-wrapper">
        <img src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
                ? asset('storage/' . $meal->photo) 
                : asset('images/default-meal.jpg') }}"
            alt="Foto de {{ $meal->name }}">
        <!-- Elemento de texto sobre a imagem -->
        <p class="day-badge">{{ $meal->day_of_week }}</p> 
    </div>
    <h3 class="card-title">{{ $meal->name }}</h3>
    <p class="card-description">{{ $meal->description }}</p>

    <!-- Formulário para adicionar ao carrinho -->
    <form method="POST" action="{{ route('cart.store') }}">
        @csrf
        <input type="hidden" name="meal_id" value="{{ $meal->id }}">
        <button type="submit" class="button add-to-cart">Adicionar ao carrinho</button>
    </form>
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

/* Texto sobre a imagem */
.day-badge {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.6); /* Transparência */
    color: #fff; /* Letras brancas */
    padding: 8px 0;
    font-size: 16px;
    text-align: center;
    font-weight: bold;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    z-index: 10;
}

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

.button:hover {
    background-color: #415bb5;
}
</style>

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

<!-- Lógica para adicionar ao carrinho pelo id  -->

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addToCartForms = document.querySelectorAll(`form[action="${document.querySelector('meta[name="cart-route"]').content}"]`);
    const cartSidebar = document.querySelector('#sideBarCart .offcanvas-body');

    addToCartForms.forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(form);

            try {
                await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData,
                });

                // Atualizar sidebar
                const response = await fetch(document.querySelector('meta[name="cart-items-route"]').content);
                const html = await response.text();
                cartSidebar.innerHTML = html;
            } catch (error) {
                console.error('Erro ao adicionar ao carrinho:', error);
            }
        });
    });
});



</script>