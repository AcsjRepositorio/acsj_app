<!-- Notas -->

<!-- data-bs-dismiss="modal" faz com que saia do modal com classe Bootstrap -->

<div class="modal fade" id="mealModal" tabindex="-1" aria-labelledby="mealModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="mealModalLabel"></h5>
                <div class="d-flex gap-2">

                    <a class="button" role="button" id="openSideBarButton">
                        <i class="bi bi-cart" style="cursor: pointer;"></i>
                    </a>

                    <span aria-hidden="true" style="cursor: pointer;" data-bs-dismiss="modal">&times;</span>
                </div>
            </div>

            <div class="modal-body d-flex flex-wrap">
                <div class="col-md-4">
                    <img id="mealModalPhoto" src="" alt="Foto do prato" class="img-fluid rounded mb-3">

                    <!-- Controle de quantidade abaixo da imagem -->

                    <div class="quantity-controls d-flex align-items-center justify-content-center mt-3">
                        <button class="btn btn-secondary btn-sm">-</button>
                        <input type="number" class="form-control text-center mx-2" value="1" min="1" style="width: 60px;">
                        <button class="btn btn-secondary btn-sm">+</button>
                    </div>
                </div>

                <div class="col-md-8 ps-4">
                    <h3 id="mealModalTitle"></h3>

                   
                    <h4 id="mealModalPrice" class="price text-success mb-2"></h4>
                    <p id="mealModalDescription" class="text-muted"></p>

                    
                    <div class="d-flex justify-content-end align-items-center mt-4 gap-2">
                        <button class="button">Pagar agora</button>
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Continuar a comprar</button>
                    </div>
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

    // Quando clicar no botão, feche o modal e abra o offcanvas
    openSideBarButton.addEventListener('click', () => {
        // Fecha o modal atual
        const bootstrapModal = bootstrap.Modal.getInstance(mealModal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }

        // Abre o offcanvas
        const bootstrapOffcanvas = new bootstrap.Offcanvas(sideBarCart);
        bootstrapOffcanvas.show();
    });
});

</script>

<style>
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
</style>