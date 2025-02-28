@php
// Consulta as bebidas (category_id = 5)
$beverages = \App\Models\Meal::where('category_id', 5)->get();
@endphp

<div class="modal fade" id="beverages" tabindex="-1" aria-labelledby="beveragesLabel" aria-hidden="true">
  <!-- Adicionada a classe modal-dialog-scrollable -->
  <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
    <div class="modal-content">
      <!-- Cabeçalho do Modal -->
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title" id="beveragesLabel" style="color: #00C49A">Bebidas</h5>
        <button type="button" class="btn-close" style="border: none;" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <!-- Corpo do Modal -->
      <div class="modal-body">
        <!-- A classe px-4 adiciona espaçamento lateral no container -->
        <div class="container px-4">
          <!-- A classe "g-4" adiciona mais espaçamento entre as colunas do que "g-3" -->
          <div class="row g-4">
            @forelse($beverages as $beverage)
            <!-- Alterado col-md-3 para col-md-4 -->
            <div class="col-md-4 col-sm-6 mb-4">
              <!-- Card com seleção: ao clicar, a função toggleSelect é chamada -->
              <div class="card h-100 shadow-sm position-relative"
                id="beverage-{{ $beverage->id }}"
                data-id="{{ $beverage->id }}"
                style="cursor: pointer;"
                onclick="toggleSelect({{ $beverage->id }})">

                <!-- Marcador de seleção (inicialmente oculto) -->
                <div id="beverage-check-{{ $beverage->id }}" class="position-absolute top-0 end-0 p-2" style="display: none;">
                  <i class="bi bi-check-circle-fill text-success" style="font-size: 1.5rem;"></i>
                </div>

                <!-- Imagem -->
                <img
                  src="{{ $beverage->photo && file_exists(public_path('storage/' . $beverage->photo)) 
                      ? asset('storage/' . $beverage->photo) 
                      : asset('images/default-meal.jpg') }}"
                  class="card-img-top"
                  alt="{{ $beverage->name }}"
                  style="height: 180px; object-fit: cover;">

                <!-- Corpo do Card -->
                <div class="card-body d-flex flex-column">
                  <h6 class="card-title mb-1">{{ $beverage->name }}</h6>
                  <p class="text-muted mb-2" style="font-size: 14px;
                      overflow: hidden;
                      display: -webkit-box;
                      -webkit-line-clamp: 4;
                      -webkit-box-orient: vertical;">
                    {{ $beverage->description }}
                  </p>
                  <!-- Exibição do preço -->
                  <div class="d-flex justify-content-end mb-3">
                    <span class="text-success text-end" style="font-size: 16px;">
                      <strong>€ {{ number_format($beverage->price, 2, ',', '.') }}</strong>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="col-12">
              <p class="text-center">Nenhuma bebida encontrada.</p>
            </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Rodapé do Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" style="border:none" onclick="submitSelected()">Adicionar seleção ao carrinho</button>
      </div>

      <!-- Formulário oculto para submeter os selecionados -->
      <form id="selected-form" action="{{ route('cart.bulk.store') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="selected_ids" id="selected-ids">
      </form>
    </div>
  </div>
</div>

<!-- Script para controle de seleção no modal -->
<script>
  // Array para armazenar os IDs selecionados
  let selectedItems = [];

  // Função para alternar seleção do card
  function toggleSelect(id) {
    const card = document.getElementById(`beverage-${id}`);
    const check = document.getElementById(`beverage-check-${id}`);
    const index = selectedItems.indexOf(id);
    if (index === -1) {
      // Selecionar o card
      selectedItems.push(id);
      card.classList.add('border', 'border-2', 'border-success');
      check.style.display = 'block';
    } else {
      // Desselecionar o card
      selectedItems.splice(index, 1);
      card.classList.remove('border', 'border-2', 'border-success');
      check.style.display = 'none';
    }
  }

  // Função para submeter os itens selecionados
  function submitSelected() {
    if (selectedItems.length === 0) {
      alert('Nenhum produto selecionado.');
      return;
    }
    // Atribui os IDs selecionados ao input hidden
    document.getElementById('selected-ids').value = JSON.stringify(selectedItems);
    document.getElementById('selected-form').submit();
  }
</script>
