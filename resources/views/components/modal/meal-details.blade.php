
<div class="modal fade" id="mealModal" tabindex="-1" aria-labelledby="mealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="mealModalLabel"></h5>
                <h3 id="mealModalTitle" style="color: #00C49A"></h3>
                <div class="d-flex gap-2">
                    <a class="button" role="button" id="openSideBarButton">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </a>
                    
                </div>
            </div>
            

            <div class="modal-body d-flex flex-wrap">
                <div class="col-md-4">
                    <img id="mealModalPhoto" src="" alt="Foto do prato" class="img-fluid rounded mb-3">

                  
                    
                </div>

                <div class="col-md-8 ps-4">
                    
                    <h4 id="mealModalPrice" class="price text-success mb-2"></h4>
                    <p id="mealModalDescription" class="text-muted"></p>

                </div>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', () => {
    const mealModal = document.getElementById('mealModal');
    const sideBarCart = document.getElementById('sideBarCart');
    const openSideBarButton = document.getElementById('openSideBarButton');

    // Quando clicar no botão do carrinho, feche o modal e abra o offcanvas
    openSideBarButton.addEventListener('click', () => {
        const bootstrapModal = bootstrap.Modal.getInstance(mealModal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }

        const bootstrapOffcanvas = new bootstrap.Offcanvas(sideBarCart);
        bootstrapOffcanvas.show();
    });

    // Evento para preencher o modal quando ele for aberto
    mealModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Botão que acionou o modal

        // Pegando dados do data-attribute que foram setados no botão "Mais detalhes"
        const mealName = button.getAttribute('data-meal-name');
        const mealDescription = button.getAttribute('data-meal-description');
        const mealPhoto = button.getAttribute('data-meal-photo');
        const mealPrice = button.getAttribute('data-meal-price');

        // Preenchendo o modal com os dados
        mealModal.querySelector('#mealModalTitle').textContent = mealName;
        mealModal.querySelector('#mealModalDescription').textContent = mealDescription;
        mealModal.querySelector('#mealModalPhoto').src = mealPhoto;
        mealModal.querySelector('#mealModalPrice').textContent = `€${mealPrice}`;
    });
});
</script>

<style>
    .button {
        border-radius: 8px;
        padding: 6px;
        color: #fff;
        border: none;
        text-decoration: none;
    }

 
</style>
