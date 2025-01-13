<!-- Usei o OFFCANVAS (SIDEBAR) -->
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

        <!-- Mensagens de sucesso ou erro -->
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (is_array($cart) && count($cart) > 0)

        <!-- Repetição para cada item do carrinho -->
        @foreach ($cart as $id => $item)
        <div class="shadow p-3 mb-5 bg-body rounded"
            id="produto-{{ $id }}"
            data-preco="{{ $item['price'] }}"> <!-- Guardamos o preço no data attribute -->

            <div class="d-flex justify-content-between ">
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

            <!-- Quantidade de itens (inicial) -->
            <span>Quantidade:
                <b id="quant-{{ $id }}">{{ $item['quantity'] }}</b>
            </span>

            <!-- Preço unitário -->
            <span>€{{ number_format($item['price'], 2) }}</span>

            <div class="d-flex justify-content-between mt-3">
                <!-- Botões para alterar quantidade -->
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

                <!-- Subtotal (inicial) -->
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

        <div class="d-flex justify-content-between mt-3">
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="btn btn-warning">Limpar Carrinho</button>
            </form>

            <!-- Campo para exibir o total do carrinho -->
            <div>
                <label for=""><b>Total do carrinho:</b></label>
                <span id="carrinho-total"><b>€ 0.00</b></span>
            </div>
        </div>

        <div class="d-flex align-items-center mt-3">
            <!-- Form para finalizar compra -->
            <form action="{{ route('checkout') }}" method="POST">
    @csrf
    <!-- Outras opções de pagamento -->
    <div class="mb-3">
        <label for="payment_method" class="form-label">Método de Pagamento</label>
        <select name="payment_method" id="payment_method" class="form-select">
            <option value="card">Cartão de Crédito</option>
            <option value="multibanco">Multibanco</option>
            <!-- Adicione mais métodos conforme necessário -->
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Finalizar Compra</button>
</form>

            <a href="#">Continuar a comprar</a>
        </div>

        @else
        <p>O carrinho está vazio.</p>
        @endif

    </div>
</div>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts para recalcular totais -->
<script>
    // Função para alterar a quantidade de acordo com clique em +/-:
    function processarQuantidade(itemId, delta) {
        const campoQuant = document.getElementById('campo-quant-' + itemId);
        let valorAtual = parseInt(campoQuant.value);

        valorAtual += delta;
        if (valorAtual < 1) {
            valorAtual = 1;
        }
        campoQuant.value = valorAtual;

        // Atualiza subtotal daquele item
        recalcularTotal(itemId, valorAtual);
    }

    // Caso o usuário digite manualmente a quantidade:
    function atualizarQuantidade(itemId) {
        const campoQuant = document.getElementById('campo-quant-' + itemId);
        let valorDigitado = parseInt(campoQuant.value);

        if (isNaN(valorDigitado) || valorDigitado < 1) {
            valorDigitado = 1;
            campoQuant.value = 1;
        }
        recalcularTotal(itemId, valorDigitado);
    }

    // Recalcula o subtotal de 1 item
    function recalcularTotal(itemId, novaQuantidade) {
        // Pega o preço do data-attribute
        const produtoDiv = document.getElementById('produto-' + itemId);
        const preco = parseFloat(produtoDiv.getAttribute('data-preco'));

        // Novo subtotal
        const novoTotal = preco * novaQuantidade;

        // Atualiza o elemento de subtotal
        const totalElement = document.getElementById('total-' + itemId);
        totalElement.textContent = '€' + novoTotal.toFixed(2);

        // Atualiza a exibição da quantidade
        const labelQuant = document.getElementById('quant-' + itemId);
        labelQuant.textContent = novaQuantidade;

        // Atualiza o total do carrinho
        recalcularTotalDoCarrinho();
    }

    // Soma os subtotais de todos os itens e exibe no 'carrinho-total'
    function recalcularTotalDoCarrinho() {
        let totalCarrinho = 0;
        const produtos = document.querySelectorAll('[id^="produto-"]');

        produtos.forEach((produto) => {
            const itemId = produto.id.replace('produto-', '');
            const preco = parseFloat(produto.getAttribute('data-preco'));
            const campoQuant = document.getElementById('campo-quant-' + itemId);
            const quantidade = parseInt(campoQuant.value) || 0;

            totalCarrinho += preco * quantidade;
        });

        const carrinhoTotal = document.getElementById('carrinho-total');
        carrinhoTotal.textContent = '€ ' + totalCarrinho.toFixed(2);
    }

    // Chama a função para recalcular o total ao carregar a página (ou offcanvas)
    document.addEventListener('DOMContentLoaded', function() {
        recalcularTotalDoCarrinho();
    });
</script>