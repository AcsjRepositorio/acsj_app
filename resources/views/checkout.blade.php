<!-- resources/views/checkout.blade.php -->

@extends('layouts.masterlayout')

@section('content')

<!-- Componente navbar -->
<x-navbar />

@php
    // Recupera o carrinho da sessão
    $cart = session()->get('cart', []);
    $countItems = is_array($cart) ? count($cart) : 0;
@endphp

<div class="container mt-4">
    <!-- Exibe erros de validação -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Exibe mensagens de sucesso/erro -->
    @if (session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger text-center">{{ session('error') }}</div>
    @endif

    @if ($countItems > 0)
        <!-- Grid dos cards dos itens (centralizados) -->
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            @foreach ($cart as $id => $item)
                <div class="col">
                    <!-- Adicionamos o atributo data-stock para controlar o estoque -->
                    <div class="card h-100 shadow-sm"
                        id="produto-{{ $id }}"
                        data-preco="{{ $item['price'] }}"
                        data-stock="{{ $item['stock'] ?? 0 }}"
                        data-nome="{{ $item['name'] }}">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">{{ $item['name'] }}</h5>
                                <form method="POST" action="{{ route('cart.destroy', $id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none;border:none;color:gray;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <!-- Imagem do item -->
                            <img src="{{ $item['photo'] ? asset('storage/' . $item['photo']) : asset('images/default-meal.jpg') }}"
                                 alt="{{ $item['name'] }}"
                                 class="img-fluid mb-3"
                                 style="max-height: 200px; object-fit: contain;">

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <!-- Botões de - e + com id para possibilitar desabilitar -->
                                        <input type="button" id="btn-minus-{{ $id }}" value="-" onclick="processarQuantidade({{ $id }}, -1)" />
                                        <input id="campo-quant-{{ $id }}" class="text-center" style="width:40px; display:inline-block;"
                                               type="text" value="{{ $item['quantity'] }}" maxlength="5" onblur="atualizarQuantidade({{ $id }})" />
                                        <input type="button" id="btn-plus-{{ $id }}" value="+" onclick="processarQuantidade({{ $id }}, 1)" />
                                    </div>
                                    <!-- Subtotal do item -->
                                    <span><b id="total-{{ $id }}">€{{ number_format($item['price'] * $item['quantity'], 2) }}</b></span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-secondary w-100 mb-2" type="button" onclick="toggleNoteField({{ $id }})">
                                <i class="bi bi-bookmark-plus"></i> Nota
                            </button>
                            <div id="noteField-{{ $id }}" style="display: none;">
                                <textarea class="form-control mb-2" rows="2" id="input-note-{{ $id }}" placeholder="Digite sua nota..."></textarea>
                            </div>
                            <select class="form-select" id="horarios-{{ $id }}">
                                <option value="" disabled selected>Selecione um horário</option>
                                <option value="12h15 - 12h30">12h15 - 12h30</option>
                                <option value="12h30 - 13h00">12h30 - 13h00</option>
                                <option value="13h00 - 13h30">13h00 - 13h30</option>
                                <option value="13h30 - 14h00">13h30 - 14h00</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Limpar Carrinho e total -->
        <div class="d-flex justify-content-center align-items-center mt-4">
            <div>
                <label><b>Total do carrinho:</b></label>
                <span id="carrinho-total"><b>€ 0.00</b></span>
            </div>
        </div>

        <!-- Formulário de pagamento -->
        <form method="POST" action="{{ route('payment.process') }}" class="mt-5" onsubmit="return prepararCamposHidden(event)">
            @csrf
            @if (!Auth::check())
                <div class="mb-3 text-center">
                    <label for="customer_name" class="form-label">Nome</label>
                    <!-- Autocomplete e localStorage -->
                    <input type="text"
                           id="customer_name"
                           name="customer_name"
                           class="form-control d-inline-block"
                           style="max-width: 300px;"
                           value="{{ old('customer_name') }}"
                           autocomplete="name"
                           required>
                </div>
                <div class="mb-3 text-center">
                    <label for="customer_email" class="form-label">E-mail</label>
                    <!-- Autocomplete e localStorage -->
                    <input type="email"
                           id="customer_email"
                           name="customer_email"
                           class="form-control d-inline-block"
                           style="max-width: 300px;"
                           value="{{ old('customer_email') }}"
                           autocomplete="email"
                           required>
                </div>
            @endif

            <!-- Seleção do método de pagamento -->
            <div class="text-center my-4">
                <h5 class="mb-3">Selecione o método de pagamento:</h5>
                <div class="row justify-content-center g-4">
                    <!-- MB WAY -->
                    <div class="col-auto text-center">
                        <div class="metodo-pg" onclick="selecionarMetodoPagamento('mbway', this)">
                            <img src="{{ asset('images/paymentmethods/mbway.png') }}"
                                 alt="MBWay"
                                 class="payment-icon">
                        </div>
                    </div>
                    <!-- CARTÃO (Visa + MasterCard) -->
                    <div class="col-auto text-center">
                        <div class="metodo-pg" onclick="selecionarMetodoPagamento('card', this)">
                            <img src="{{ asset('images/paymentmethods/visa.png') }}"
                                 alt="Visa"
                                 class="payment-icon me-1">
                            <img src="{{ asset('images/paymentmethods/mastercard.png') }}"
                                 alt="MasterCard"
                                 class="payment-icon">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campo hidden para o método de pagamento -->
            <input type="hidden" name="payment_method" id="payment_method" value="">

            <!-- Campo para telefone MBWay (exibido somente se MBWay for selecionado) -->
            <div id="mbway-phone-field" style="display: none;" class="mb-3 text-center">
                <label for="mbway_phone" class="form-label">Telefone MBWay</label>
                <input type="text"
                       id="mbway_phone"
                       name="mbway_phone"
                       class="form-control d-inline-block"
                       style="max-width: 300px;"
                       placeholder="ex: 912345678"
                       value="{{ old('mbway_phone') }}">
            </div>

            <!-- Inputs hidden para note, pickup_time e quantity de cada item -->
            <div id="hidden-fields-container"></div>

            <!-- Botão "Finalizar Compra": inicia desativado -->
            <div class="text-center">
                <button type="submit" id="finalizar-btn" class="btn btn-primary px-5" disabled style="opacity: 0.6;">
                    Finalizar Compra
                </button>
            </div>
        </form>
    @else
        <div class="shadow-lg p-4 mt-5 bg-body rounded text-center mx-auto" style="max-width: 350px; width: 100%;">
            <img src="/images/icons/emptydish.png" alt="prato vazio" class="img-fluid empty-cart-img" style="max-height: 140px; object-fit: contain;">
            <h5 class="mb-3 text-secondary">Por hora, o seu prato está vazio!</h5>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3" role="button" aria-label="Ver Refeições">Ver Refeições</a>
        </div>
    @endif
</div>

@endsection

<!-- Bootstrap e Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- CSS Customizado -->
<style>
    /* Tamanho fixo e uniforme para as imagens dos métodos de pagamento */
    .payment-icon {
        width: 120px;
        height: 40px;
        object-fit: contain;
        transition: transform 0.2s;
    }

    .payment-icon:hover {
        transform: scale(1.05);
    }

    /* Container dos métodos de pagamento */
    .metodo-pg {
        cursor: pointer;
        padding: 5px;
        transition: border 0.2s;
    }

    /* Borda de destaque para o método selecionado */
    .metodo-pg.selected {
        border: 2px solid #0d6efd;
        border-radius: 6px;
    }
</style>

<!-- JavaScript do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript Customizado -->
<script>
    // Função para aumentar ou diminuir a quantidade, considerando o estoque (stock)
    function processarQuantidade(id, delta) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        let quantidadeAtual = parseInt(campoQuant.value) || 1;
        const produtoDiv = document.getElementById('produto-' + id);
        const maxStock = parseInt(produtoDiv.getAttribute('data-stock')) || 0;

        quantidadeAtual += delta;

        // Garante que a quantidade não seja menor que 1
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

    // Habilita ou desabilita os botões conforme a quantidade atual e estoque disponível
    function atualizarBotao(id, quantidadeAtual, maxStock) {
        const btnPlus = document.getElementById('btn-plus-' + id);
        const btnMinus = document.getElementById('btn-minus-' + id);

        btnPlus.disabled = (quantidadeAtual >= maxStock);
        btnMinus.disabled = (quantidadeAtual <= 1);
    }

    // Atualiza a quantidade, recalcula o subtotal e envia a atualização ao servidor
    function atualizarQuantidade(id, quantidade = null) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        let newQuantity = quantidade !== null ? quantidade : parseInt(campoQuant.value) || 1;
        const produtoDiv = document.getElementById('produto-' + id);
        const preco = parseFloat(produtoDiv.getAttribute('data-preco'));
        const maxStock = parseInt(produtoDiv.getAttribute('data-stock')) || 0;

        if (newQuantity > maxStock) {
            newQuantity = maxStock;
            campoQuant.value = maxStock;
        }

        const subtotal = preco * newQuantity;
        document.getElementById('total-' + id).textContent = '€' + subtotal.toFixed(2);
        recalcularTotalCarrinho();

        // Atualização via AJAX para o servidor
        fetch("{{ route('cart.updateQuantity') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                meal_id: id,
                quantity: newQuantity
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Erro ao atualizar quantidade:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro na comunicação com o servidor:', error);
        });

        atualizarBotao(id, newQuantity, maxStock);
    }

    // Recalcula o total do carrinho
    function recalcularTotalCarrinho() {
        let totalCarrinho = 0;
        document.querySelectorAll('[id^="produto-"]').forEach(produto => {
            const preco = parseFloat(produto.getAttribute('data-preco'));
            const campoQuant = produto.querySelector('[id^="campo-quant-"]');
            const quantidade = parseInt(campoQuant.value) || 1;
            totalCarrinho += preco * quantidade;
        });
        document.getElementById('carrinho-total').textContent = '€ ' + totalCarrinho.toFixed(2);
    }

    // Alterna a exibição do campo Nota
    function toggleNoteField(id) {
        const noteField = document.getElementById('noteField-' + id);
        noteField.style.display = (noteField.style.display === 'none') ? 'block' : 'none';
    }

    // Seleciona o método de pagamento e habilita o botão de finalizar
    function selecionarMetodoPagamento(metodo, elementoClicado) {
        document.querySelectorAll('.metodo-pg').forEach(el => {
            el.classList.remove('selected');
        });
        if (elementoClicado.classList.contains('metodo-pg')) {
            elementoClicado.classList.add('selected');
        } else {
            elementoClicado.closest('.metodo-pg').classList.add('selected');
        }
        document.getElementById('payment_method').value = metodo;
        document.getElementById('mbway-phone-field').style.display = (metodo === 'mbway') ? 'block' : 'none';

        const btn = document.getElementById('finalizar-btn');
        btn.disabled = false;
        btn.style.opacity = 1;
    }

    // Prepara os inputs hidden e valida os horários antes de submeter o formulário
    function prepararCamposHidden(event) {
        event.preventDefault();
        const container = document.getElementById('hidden-fields-container');
        container.innerHTML = '';
        let itensSemHorario = [];

        document.querySelectorAll('[id^="produto-"]').forEach(produto => {
            const itemId = produto.id.replace('produto-', '');
            const nomeItem = produto.getAttribute('data-nome') || ('Item ' + itemId);

            const note = document.getElementById('input-note-' + itemId)?.value || '';
            const pickupTime = document.getElementById('horarios-' + itemId)?.value || '';
            const quantity = document.getElementById('campo-quant-' + itemId)?.value || '1';

            if (!pickupTime) {
                itensSemHorario.push(nomeItem);
            }

            let hiddenNote = document.createElement('input');
            hiddenNote.type = 'hidden';
            hiddenNote.name = `items[${itemId}][note]`;
            hiddenNote.value = note;
            container.appendChild(hiddenNote);

            let hiddenPickup = document.createElement('input');
            hiddenPickup.type = 'hidden';
            hiddenPickup.name = `items[${itemId}][pickup_time]`;
            hiddenPickup.value = pickupTime;
            container.appendChild(hiddenPickup);

            let hiddenQty = document.createElement('input');
            hiddenQty.type = 'hidden';
            hiddenQty.name = `items[${itemId}][quantity]`;
            hiddenQty.value = quantity;
            container.appendChild(hiddenQty);
        });

        if (itensSemHorario.length > 0) {
            alert("Por favor, selecione o horário nos itens:\n\n- " + itensSemHorario.join("\n- "));
            return false;
        }

        event.target.submit();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Atualiza botões +/- e recalcula total ao carregar
        document.querySelectorAll('[id^="produto-"]').forEach(produto => {
            const id = produto.id.split('-')[1];
            const campoQuant = document.getElementById('campo-quant-' + id);
            const quantidade = parseInt(campoQuant.value) || 1;
            const maxStock = parseInt(produto.getAttribute('data-stock')) || 0;
            atualizarBotao(id, quantidade, maxStock);
        });
        recalcularTotalCarrinho();

        // Se existirem valores antigos no formulário (ex: erro de validação),
        // restaura o método de pagamento escolhido e habilita o botão
        @if(old('payment_method') === 'mbway')
            document.getElementById('payment_method').value = 'mbway';
            document.getElementById('mbway-phone-field').style.display = 'block';
            document.getElementById('finalizar-btn').disabled = false;
            document.getElementById('finalizar-btn').style.opacity = 1;
        @elseif(old('payment_method') === 'multibanco' || old('payment_method') === 'card')
            document.getElementById('payment_method').value = '{{ old('payment_method') }}';
            document.getElementById('finalizar-btn').disabled = false;
            document.getElementById('finalizar-btn').style.opacity = 1;
        @endif

        // Carrega localStorage (apenas se o usuário não estiver logado, logo existem esses campos)
        @if (!Auth::check())
            const inputName = document.getElementById('customer_name');
            const inputEmail = document.getElementById('customer_email');

            if (localStorage.getItem('customer_name')) {
                inputName.value = localStorage.getItem('customer_name');
            }
            if (localStorage.getItem('customer_email')) {
                inputEmail.value = localStorage.getItem('customer_email');
            }

            // Sempre que o usuário digitar algo, salvamos em localStorage
            inputName.addEventListener('input', function(){
                localStorage.setItem('customer_name', this.value);
            });
            inputEmail.addEventListener('input', function(){
                localStorage.setItem('customer_email', this.value);
            });
        @endif

        // Se o carrinho tiver mais de 1 item, perguntamos ao usuário se quer replicar o horário
        let cartSize = {{ $countItems }};
        if (cartSize > 1) {
            const allSelects = document.querySelectorAll('select[id^="horarios-"]');
            allSelects.forEach(select => {
                select.addEventListener('change', () => {
                    if (select.value) {
                        const userConfirmed = confirm("Deseja selecionar o mesmo horário para todos os pedidos?");
                        if (userConfirmed) {
                            const selectedValue = select.value;
                            // Aplica esse valor a todos os selects
                            allSelects.forEach(otherSelect => {
                                otherSelect.value = selectedValue;
                            });
                        }
                    }
                });
            });
        }
    });
</script>