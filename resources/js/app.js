import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;


Alpine.start();


document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    const cartContainer = document.querySelector('#sideBarCart .offcanvas-body');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.preventDefault();

            const mealId = button.dataset.mealId;

            fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ meal_id: mealId })
            })
            .then(response => response.text())
            .then(html => {
                cartContainer.innerHTML = html; // Atualiza o conteúdo do carrinho
            })
            .catch(error => console.error('Erro ao adicionar ao carrinho:', error));
        });
    });
});


