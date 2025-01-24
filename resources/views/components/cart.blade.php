<!-- resources/views/cart.blade.php -->
<div class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasCart"
    aria-labelledby="offcanvasCartLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasCartLabel">Seu Carrinho</h5>
        <button type="button" class="btn-close text-reset"
            data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">

        <!-- Mensagens de sucesso/erro -->
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (is_array($cart) && count($cart) > 0)
        <!-- LISTA DE ITENS -->
        @foreach ($cart as $id => $item)
        <div class="shadow p-3 mb-5 bg-body rounded"
            id="produto-{{ $id }}"
            data-preco="{{ $item['price'] }}">

            <div class="d-flex justify-content-between">
                <b>{{ $item['name'] }}</b>
                <!-- Form de excluir item -->
                <form method="POST" action="{{ route('cart.destroy', $id) }}">
                    @csrf
                    @method('DELETE')
                    <a href="javascript:void(0);" style="color: gray; text-decoration: none;"
                        onclick="this.closest('form').submit();">
                        <i class="bi bi-trash"></i>
                    </a>
                </form>
            </div>

            <!-- Imagem -->
            <img src="{{ $item['photo'] ? asset('storage/' . $item['photo']) : asset('images/default-meal.jpg') }}"
                alt="{{ $item['name'] }}"
                width="80">

            <div class="d-flex justify-content-between mt-3">
                <!-- Botões +/- -->
                <div data-app="product.quantity" id="quantidade-{{ $id }}">
                    <input type="button" value="-" onclick="processarQuantidade({{ $id }}, -1)" />
                    <input id="campo-quant-{{ $id }}"
                        class="text"
                        size="1"
                        type="text"
                        value="{{ $item['quantity'] }}"
                        maxlength="5"
                        onblur="atualizarQuantidade({{ $id }})" />
                    <input type="button" value="+" onclick="processarQuantidade({{ $id }}, 1)" />
                </div>

                <!-- Subtotal -->
                <div class="gap-3">
                    <span><b>Subtotal:</b></span>
                    <span>
                        <b id="total-{{ $id }}">
                            €{{ number_format($item['price'] * $item['quantity'], 2) }}
                        </b>
                    </span>
                </div>
            </div>

        
        </div>
        @endforeach

        <!-- Botão Limpar Carrinho e Total -->
        <div class="d-flex justify-content-between mt-3">
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="btn btn-warning">Limpar Carrinho</button>
            </form>

            <div>
                <label><b>Total do carrinho:</b></label>
                <span id="carrinho-total"><b>€ 0.00</b></span>
            </div>
        </div>

        <!-- Form para finalizar compra -->
        


        <!-- btn para seguir para checkout -->

        <a type="button" class="btn btn-primary" href= "{{route ('checkout') }}">Checkout</a>
        
        
        @else
        <p class="text-muted">O carrinho está vazio.</p>
        @endif
    </div>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Função para aumentar ou diminuir a quantidade
    function processarQuantidade(id, delta) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        let quantidadeAtual = parseInt(campoQuant.value) || 1;
        quantidadeAtual += delta;

        // Garantir que a quantidade não seja menor que 1
        if (quantidadeAtual < 1) {
            quantidadeAtual = 1;
        }

        // Atualiza o campo de quantidade
        campoQuant.value = quantidadeAtual;

        // Atualiza o subtotal do item e o total do carrinho
        atualizarQuantidade(id, quantidadeAtual);
    }

    // Função para atualizar a quantidade ao sair do campo de texto
    function atualizarQuantidade(id, quantidade) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        const produtoDiv = document.getElementById('produto-' + id);
        const preco = parseFloat(produtoDiv.getAttribute('data-preco'));

        // Calcula o novo subtotal
        const subtotal = preco * quantidade;

        // Atualiza o subtotal do item
        const totalElement = document.getElementById('total-' + id);
        totalElement.textContent = '€' + subtotal.toFixed(2);

        // Atualiza o total do carrinho
        recalcularTotalCarrinho();

        // Atualiza a quantidade no servidor
        atualizarQuantidadeServidor(id, quantidade);
    }

    // Função para recalcular o total do carrinho
    function recalcularTotalCarrinho() {
        let totalCarrinho = 0;

        // Itera sobre todos os itens no carrinho
        document.querySelectorAll('[id^="produto-"]').forEach((produto) => {
            const preco = parseFloat(produto.getAttribute('data-preco'));
            const campoQuant = produto.querySelector('[id^="campo-quant-"]');
            const quantidade = parseInt(campoQuant.value) || 1;

            totalCarrinho += preco * quantidade;
        });

        // Atualiza o total do carrinho na interface
        const carrinhoTotal = document.getElementById('carrinho-total');
        carrinhoTotal.textContent = '€ ' + totalCarrinho.toFixed(2);
    }

    // Função para enviar a nova quantidade ao servidor
    function atualizarQuantidadeServidor(id, quantidade) {
        fetch("{{ route('cart.updateQuantity') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                // ATENÇÃO: trocamos "item_id" para "meal_id"
                meal_id: id,
                quantity: quantidade,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Quantidade atualizada com sucesso no servidor.');
            } else {
                console.error('Erro ao atualizar quantidade no servidor:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro na comunicação com o servidor:', error);
        });
    }

    // Inicializa o total do carrinho ao carregar a página
    document.addEventListener('DOMContentLoaded', function () {
        recalcularTotalCarrinho();
    });

    // Exemplo de função para exibir/esconder nota
    function toggleNoteField(button, id) {
        const noteField = document.getElementById('noteField-' + id);
        if (noteField.style.display === 'none') {
            noteField.style.display = 'block';
        } else {
            noteField.style.display = 'none';
        }
    }

    // Exemplo de função para capturar horário selecionado
    function atualizarHorarioSelecionado(select, id) {
        const selectedHorario = select.value;
        console.log('Horário selecionado para o item ' + id + ': ' + selectedHorario);
    }
</script>
