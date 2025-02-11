<!-- resources/views/cart.blade.php -->
<div class="offcanvas offcanvas-end" data-bs-backdrop="false" tabindex="-1" id="offcanvasCart" aria-labelledby="offcanvasCartLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasCartLabel">Seu Carrinho</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
                     data-preco="{{ $item['price'] }}"
                     data-stock="{{ $item['stock'] ?? 0 }}">
                    
                    <div class="d-flex justify-content-between">
                        <b>{{ $item['name'] }}</b>
                        <!-- Form de excluir item -->
                        <form method="POST" action="{{ route('cart.destroy', $id) }}">
                            @csrf
                            @method('DELETE')
                            <a href="javascript:void(0);" style="color: gray; text-decoration: none;" onclick="this.closest('form').submit();">
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
                            <input type="button" id="btn-minus-{{ $id }}" value="-" onclick="processarQuantidade({{ $id }}, -1)" />
                            <input id="campo-quant-{{ $id }}"
                                   class="text"
                                   size="1"
                                   type="text"
                                   value="{{ $item['quantity'] }}"
                                   maxlength="5"
                                   onblur="atualizarQuantidade({{ $id }})" />
                            <input type="button" id="btn-plus-{{ $id }}" value="+" onclick="processarQuantidade({{ $id }}, 1)" />
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

            <!-- Botão Limpar vae Total -->
            <div class="d-flex justify-content-between mt-3">
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button type="submit" class="btn btn-link">Limpar Carrinho</button>
                </form>

                <div>
                    <label><b>Total do carrinho:</b></label>
                    <span id="carrinho-total"><b>€ 0.00</b></span>
                </div>
            </div>

            <!-- Botão para Checkout -->
            <a type="button" class="btn btn-primary btn-lg btn-block" href="{{ route('checkout') }}">Checkout</a>
        @else
            <div class="shadow-lg p-4 mt-5 bg-body rounded text-center mx-auto" style="max-width: 350px; width: 100%;">
                <img src="/images/icons/emptydish.png" alt="prato Vazio" class="img-fluid empty-cart-img" style="max-height: 140px; object-fit: contain;">
                <h5 class="mb-3 text-secondary">Por hora, o seu prato está vazio!</h5>
                <button type="button"  class="button mt-3 " data-bs-dismiss="offcanvas" aria-label="Close">Ver Refeições</button>
            </div>

            <style>
                .empty-cart-img {
                    filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.2));
                    transition: transform 0.3s ease-in-out;
                }
                .empty-cart-img:hover {
                    transform: scale(1.05);
                }
            </style>
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
        const produtoDiv = document.getElementById('produto-' + id);
        const maxStock = parseInt(produtoDiv.getAttribute('data-stock')) || 0;
        
        quantidadeAtual += delta;
        
        // Garantir que a quantidade não seja menor que 1
        if (quantidadeAtual < 1) {
            quantidadeAtual = 1;
        }
        // Se a quantidade ultrapassar o estoque, corrige para o valor máximo
        if (quantidadeAtual > maxStock) {
            quantidadeAtual = maxStock;
        }
        campoQuant.value = quantidadeAtual;
        
        atualizarBotao(id, quantidadeAtual, maxStock);
        atualizarQuantidade(id, quantidadeAtual);
    }

    // Habilita ou desabilita os botões conforme a quantidade atual
    function atualizarBotao(id, quantidadeAtual, maxStock) {
        const btnPlus = document.getElementById('btn-plus-' + id);
        const btnMinus = document.getElementById('btn-minus-' + id);
        
        // Desabilita o botão de incremento se a quantidade for igual ou superior ao estoque
        if (quantidadeAtual >= maxStock) {
            btnPlus.disabled = true;
        } else {
            btnPlus.disabled = false;
        }
        // Desabilita o botão de decremento se a quantidade for 1
        if (quantidadeAtual <= 1) {
            btnMinus.disabled = true;
        } else {
            btnMinus.disabled = false;
        }
    }

    // Função para atualizar a quantidade ao sair do campo de texto
    function atualizarQuantidade(id, quantidade) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        const produtoDiv = document.getElementById('produto-' + id);
        const preco = parseFloat(produtoDiv.getAttribute('data-preco'));
        const maxStock = parseInt(produtoDiv.getAttribute('data-stock')) || 0;
        
        // Se o usuário digitar uma quantidade maior que o estoque, corrige para o máximo
        if (quantidade > maxStock) {
            quantidade = maxStock;
            campoQuant.value = maxStock;
        }
        
        // Calcula e atualiza o subtotal do item
        const subtotal = preco * quantidade;
        const totalElement = document.getElementById('total-' + id);
        totalElement.textContent = '€' + subtotal.toFixed(2);
        
        recalcularTotalCarrinho();
        atualizarQuantidadeServidor(id, quantidade);
        atualizarBotao(id, quantidade, maxStock);
    }

    // Recalcula o total do carrinho
    function recalcularTotalCarrinho() {
        let totalCarrinho = 0;
        document.querySelectorAll('[id^="produto-"]').forEach((produto) => {
            const preco = parseFloat(produto.getAttribute('data-preco'));
            const campoQuant = produto.querySelector('[id^="campo-quant-"]');
            const quantidade = parseInt(campoQuant.value) || 1;
            totalCarrinho += preco * quantidade;
        });
        const carrinhoTotal = document.getElementById('carrinho-total');
        carrinhoTotal.textContent = '€ ' + totalCarrinho.toFixed(2);
    }

    // Envia a nova quantidade ao servidor
    function atualizarQuantidadeServidor(id, quantidade) {
        fetch("{{ route('cart.updateQuantity') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
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

    // Ao carregar a página, verifica e atualiza o estado dos botões para cada item
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[id^="produto-"]').forEach((produto) => {
            const id = produto.id.split('-')[1];
            const campoQuant = document.getElementById('campo-quant-' + id);
            const quantidade = parseInt(campoQuant.value) || 1;
            const maxStock = parseInt(produto.getAttribute('data-stock')) || 0;
            atualizarBotao(id, quantidade, maxStock);
        });
        recalcularTotalCarrinho();
    });

    // Funções auxiliares (para nota ou horário, se necessário)
    function toggleNoteField(button, id) {
        const noteField = document.getElementById('noteField-' + id);
        if (noteField.style.display === 'none') {
            noteField.style.display = 'block';
        } else {
            noteField.style.display = 'none';
        }
    }

    function atualizarHorarioSelecionado(select, id) {
        const selectedHorario = select.value;
        console.log('Horário selecionado para o item ' + id + ': ' + selectedHorario);
    }
</script>
