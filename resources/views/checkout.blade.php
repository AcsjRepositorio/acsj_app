<!-- resources/views/checkout.blade.php -->

<div class="container">
    <h1>Checkout</h1>

    <!-- BLOCO DE ERROS DE VALIDAÇÃO (NOVO) -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Mensagens de sucesso/erro do sistema -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        // Recuperar carrinho da sessão (ou do controller)
        $cart = session()->get('cart', []);
    @endphp

    @if (is_array($cart) && count($cart) > 0)
        <!-- Loop dos itens do carrinho (fora do form de pagamento) -->
        @foreach ($cart as $id => $item)
            <div class="shadow p-3 mb-5 bg-body rounded" 
                 id="produto-{{ $id }}" 
                 data-preco="{{ $item['price'] }}">

                <!-- Título + botão remover item -->
                <div class="d-flex justify-content-between">
                    <b>{{ $item['name'] }}</b>

                    <!-- Form (ou link) para remover item SEM aninhar -->
                    <form method="POST" action="{{ route('cart.destroy', $id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:gray;">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

                <!-- Imagem (opcional) -->
                <img src="{{ $item['photo'] ? asset('storage/' . $item['photo']) : asset('images/default-meal.jpg') }}"
                     alt="{{ $item['name'] }}"
                     width="80">

                <!-- Quantidade e subtotal -->
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

                <!-- Nota (fora do form) -->
                <div class="mb-3 mt-3">
                    <button class="btn btn-outline-secondary" type="button" onclick="toggleNoteField(this, {{ $id }})">
                        <i class="bi bi-bookmark-plus"></i> Insira uma nota
                    </button>
                    <div class="mt-2" id="noteField-{{ $id }}" style="display: none;">
                        <!-- Textarea normal, mas sem name="..." -->
                        <!-- Vamos copiar o valor depois, via JS -->
                        <textarea class="form-control"
                                  rows="3"
                                  id="input-note-{{ $id }}"
                                  placeholder="Digite sua nota aqui..."></textarea>
                    </div>
                </div>

                <!-- Select com horários (fora do form) -->
                <div class="mt-3">
                    <label for="horarios-{{ $id }}" class="form-label">Escolha um horário</label>
                    <select class="form-select"
                            id="horarios-{{ $id }}"
                            onchange="atualizarHorarioSelecionado(this, {{ $id }})">
                        <option value="" disabled selected>Selecione um horário</option>
                        <option value="12h15 - 12h30">12h15 - 12h30</option>
                        <option value="12h30 - 13h00">12h30 - 13h00</option>
                        <option value="13h00 - 13h30">13h00 - 13h30</option>
                        <option value="13h30 - 14h00">13h30 - 14h00</option>
                    </select>
                </div>
            </div>
        @endforeach

        <!-- Botão Limpar Carrinho (fora do form) -->
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

        <!-- AGORA SIM: Form principal para FINALIZAR COMPRA -->
        <!-- Ele não está envolvendo o loop dos itens (para evitar forms aninhados) -->
        <form method="POST" action="{{ route('payment.process') }}" class="mt-4" onsubmit="prepararCamposHidden()">
            @csrf

            <!-- Se usuário não estiver logado, pedir nome/e-mail -->
            @if (!Auth::check())
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Nome</label>
                    <!-- USAMOS old() PARA REPREENCHER SE VALIDAÇÃO FALHAR -->
                    <input type="text"
                           id="customer_name"
                           name="customer_name"
                           class="form-control"
                           value="{{ old('customer_name') }}"
                           required>
                </div>
                <div class="mb-3">
                    <label for="customer_email" class="form-label">E-mail</label>
                    <input type="email"
                           id="customer_email"
                           name="customer_email"
                           class="form-control"
                           value="{{ old('customer_email') }}"
                           required>
                </div>
            @endif

            <!-- Método de pagamento -->
            <div class="mb-3">
                <label for="payment_method" class="form-label">Método de Pagamento</label>
                <select name="payment_method" id="payment_method" class="form-select" required>
                    <option value="" disabled selected>Selecione o método</option>
                    <option value="multibanco" {{ old('payment_method') == 'multibanco' ? 'selected' : '' }}>
                        Multibanco
                    </option>
                    <option value="mbway" {{ old('payment_method') == 'mbway' ? 'selected' : '' }}>
                        MBWay
                    </option>
                    <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>
                        Cartão de Crédito
                    </option>
                </select>
            </div>

            <!-- Aqui guardaremos inputs hidden para note e pickup_time de cada item -->
            <div id="hidden-fields-container"></div>
            <div id="noteField-{{ $id }}"></div>

            <button type="submit" class="btn btn-primary w-100">Finalizar Compra</button>
        </form>
    @else
        <p class="text-muted">O carrinho está vazio.</p>
    @endif
</div>

<!-- Bootstrap CSS e Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // 1) Botões +/- (igual ao seu código)
    function processarQuantidade(id, delta) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        let quantidadeAtual = parseInt(campoQuant.value) || 1;
        quantidadeAtual += delta;
        if (quantidadeAtual < 1) {
            quantidadeAtual = 1;
        }
        campoQuant.value = quantidadeAtual;
        atualizarQuantidade(id, quantidadeAtual);
    }

    function atualizarQuantidade(id, quantidade) {
        const produtoDiv = document.getElementById('produto-' + id);
        const preco      = parseFloat(produtoDiv.getAttribute('data-preco'));
        const subtotal   = preco * quantidade;

        // Atualizar subtotal
        document.getElementById('total-' + id).textContent = '€' + subtotal.toFixed(2);

        // Recalcular total do carrinho
        recalcularTotalCarrinho();

        // Atualizar servidor
        atualizarQuantidadeServidor(id, quantidade);
    }

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
            if (!data.success) {
                console.error('Erro ao atualizar quantidade:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro comunicação com o servidor:', error);
        });
    }

    function recalcularTotalCarrinho() {
        let totalCarrinho = 0;
        document.querySelectorAll('[id^="produto-"]').forEach((produto) => {
            const preco = parseFloat(produto.getAttribute('data-preco'));
            const campoQuant = produto.querySelector('[id^="campo-quant-"]');
            const quantidade = parseInt(campoQuant.value) || 1;
            totalCarrinho += (preco * quantidade);
        });
        document.getElementById('carrinho-total').textContent = '€ ' + totalCarrinho.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function () {
        recalcularTotalCarrinho();
    });

    // 2) Mostrar/esconder textarea de nota
    function toggleNoteField(button, id) {
        const noteField = document.getElementById('noteField-' + id);
        noteField.style.display = (noteField.style.display === 'none') ? 'block' : 'none';
    }

    // 3) Capturar escolha de horário (opcional)
    function atualizarHorarioSelecionado(select, id) {
        console.log('Horário para item ' + id + ': ' + select.value);
    }

    // 4) Antes de submeter o FORM de pagamento, criamos inputs hidden
    //    para "note" e "pickup_time" de cada item
    function prepararCamposHidden() {
        const container = document.getElementById('hidden-fields-container');
        container.innerHTML = ''; // reset

        // Loop em cada produto
        document.querySelectorAll('[id^="produto-"]').forEach((produto) => {
            const itemId     = produto.id.replace('produto-', '');
            const note       = document.getElementById('input-note-' + itemId)?.value || '';
            const pickupTime = document.getElementById('horarios-' + itemId)?.value || '';
            const quantity   = document.getElementById('campo-quant-' + itemId)?.value || '1';

            // Criar <input type="hidden" name="items[itemId][note]">
            const hiddenNote = document.createElement('input');
            hiddenNote.type  = 'hidden';
            hiddenNote.name  = `items[${itemId}][note]`;
            hiddenNote.value = note;
            container.appendChild(hiddenNote);

            // Criar <input type="hidden" name="items[itemId][pickup_time]">
            const hiddenPickup = document.createElement('input');
            hiddenPickup.type  = 'hidden';
            hiddenPickup.name  = `items[${itemId}][pickup_time]`;
            hiddenPickup.value = pickupTime;
            container.appendChild(hiddenPickup);

            // Se no PaymentController você usa 'quantity', guarde também:
            const hiddenQty = document.createElement('input');
            hiddenQty.type  = 'hidden';
            hiddenQty.name  = `items[${itemId}][quantity]`;
            hiddenQty.value = quantity;
            container.appendChild(hiddenQty);
        });
    }
</script>

