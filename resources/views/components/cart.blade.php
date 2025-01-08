<!-- Usei o  OFFCANVAS (SIDEBAR)   -->
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

        <!-- código do carrinho  -->
        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (is_array($cart) && count($cart) > 0)


        <!-- Exemplo de repetição para cada item do carrinho -->
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

                <!-- Total (inicial) -->
                <div class="gap-3">
                    <span><b>Subtoal:</b></span>
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



        @else
        <p>O carrinho está vazio.</p>
        @endif


    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function process(quant) {
        var value = parseInt(document.getElementById("quant").value);
        value += quant;
        if (value < 1) {
            document.getElementById("quant").value = 1;
        } else {
            document.getElementById("quant").value = value;
        }
    }
</script>

<script>
    // Continua igual a sua função que atualiza subtotal de 1 item
    function processarQuantidade(itemId, delta) {
        const campoQuant = document.getElementById('campo-quant-' + itemId);
        let valorAtual = parseInt(campoQuant.value);

        // Atualiza o valor (somando delta: -1 ou +1)
        valorAtual += delta;
        if (valorAtual < 1) {
            valorAtual = 1;
        }
        campoQuant.value = valorAtual;

        // Atualiza subtotal daquele item
        recalcularTotal(itemId, valorAtual);
    }

    function atualizarQuantidade(itemId) {
        const campoQuant = document.getElementById('campo-quant-' + itemId);
        let valorDigitado = parseInt(campoQuant.value);

        if (isNaN(valorDigitado) || valorDigitado < 1) {
            valorDigitado = 1;
            campoQuant.value = 1;
        }
        recalcularTotal(itemId, valorDigitado);
    }

    function recalcularTotal(itemId, novaQuantidade) {
        // Pega o preço a partir do data-attribute
        const produtoDiv = document.getElementById('produto-' + itemId);
        const preco = parseFloat(produtoDiv.getAttribute('data-preco'));

        // Calcula o novo subtotal
        const novoTotal = preco * novaQuantidade;

        // Atualiza o elemento de subtotal do item
        const totalElement = document.getElementById('total-' + itemId);
        totalElement.textContent = '€' + novoTotal.toFixed(2);

        // Atualiza a exibição da quantidade
        const labelQuant = document.getElementById('quant-' + itemId);
        labelQuant.textContent = novaQuantidade;

        // *** Chama a função para recalcular o total do carrinho ***
        recalcularTotalDoCarrinho();
    }

    // Função para recalcular o total do carrinho somando todos os itens
    function recalcularTotalDoCarrinho() {
        let totalCarrinho = 0;

        // Seleciona todos os "cards" de produto (a div com id="produto-XX")
        const produtos = document.querySelectorAll('[id^="produto-"]');
        
        produtos.forEach((produto) => {
            // ID do produto (ex.: produto-1 => 1)
            const itemId = produto.id.replace('produto-', '');
            
            // Preço unitário
            const preco = parseFloat(produto.getAttribute('data-preco'));
            
            // Quantidade digitada no input
            const campoQuant = document.getElementById('campo-quant-' + itemId);
            const quantidade = parseInt(campoQuant.value) || 0;
            
            // Soma no total do carrinho
            totalCarrinho += preco * quantidade;
        });

        // Atualiza o span do total do carrinho
        const carrinhoTotal = document.getElementById('carrinho-total');
        carrinhoTotal.textContent = '€' + totalCarrinho.toFixed(2);
    }
</script>
