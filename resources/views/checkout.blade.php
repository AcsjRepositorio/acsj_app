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
        <!-- ROW PRINCIPAL COM 2 COLUNAS: ESQUERDA (itens) E DIREITA (checkout) -->
        <div class="row g-4">
            <!-- COLUNA ESQUERDA -->
            <div class="col-lg-8">
                <!-- Grid dos cards dos itens -->
                <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center">
                    @foreach ($cart as $id => $item)
                        <div class="col">
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
                                    @if(str_contains($item['photo'], 'images/'))
                                        <img src="{{ asset($item['photo']) }}"
                                             alt="{{ $item['name'] }}"
                                             class="img-fluid mb-3"
                                             style="max-height: 200px; object-fit: contain;">
                                    @else
                                        <img src="{{ asset('storage/' . $item['photo']) }}"
                                             alt="{{ $item['name'] }}"
                                             class="img-fluid mb-3"
                                             style="max-height: 200px; object-fit: contain;">
                                    @endif

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <!-- Botões de - e + com id para possibilitar desabilitar -->
                                                <input type="button" id="btn-minus-{{ $id }}" value="-" onclick="processarQuantidade({{ $id }}, -1)" />
                                                <input id="campo-quant-{{ $id }}"
                                                       class="text-center"
                                                       style="width:40px; display:inline-block;"
                                                       type="text"
                                                       value="{{ $item['quantity'] }}"
                                                       maxlength="5"
                                                       onblur="atualizarQuantidade({{ $id }})" />
                                                <input type="button" id="btn-plus-{{ $id }}" value="+" onclick="processarQuantidade({{ $id }}, 1)" />
                                            </div>
                                            <!-- Subtotal do item -->
                                            <span>
                                                <b id="total-{{ $id }}">
                                                    €{{ number_format($item['price'] * $item['quantity'], 2) }}
                                                </b>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button class="btn btn-outline-secondary w-100 mb-2" type="button" onclick="toggleNoteField({{ $id }})">
                                        <i class="bi bi-bookmark-plus"></i> Insira uma nota
                                    </button>
                                    <div id="noteField-{{ $id }}" style="display: none;">
                                        <textarea class="form-control mb-2"
                                                  rows="2"
                                                  id="input-note-{{ $id }}"
                                                  placeholder="Digite sua nota..."></textarea>
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
            </div>

            <!-- COLUNA DIREITA -->
            <div class="col-lg-4">
                <!-- Seção: Selecione o método de pagamento (com radios) -->
                <div class="shadow-sm p-3 mb-4 bg-body rounded">
                    <h5 class="mb-3">Selecione o método de pagamento</h5>
                    <div class="d-flex justify-content-around align-items-center">
                        <!-- Radio MBWay -->
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="paymentMethod"
                                   id="radioMbway"
                                   value="mbway"
                                   onclick="selecionarMetodoPagamento(this.value)">
                            <label class="form-check-label" for="radioMbway">
                                <img src="{{ asset('images/paymentmethods/mbway.png') }}"
                                     alt="MBWay"
                                     class="payment-icon">
                            </label>
                        </div>
                        <!-- Radio Visa -->
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="paymentMethod"
                                   id="radioVisa"
                                   value="card"
                                   onclick="selecionarMetodoPagamento(this.value)">
                            <label class="form-check-label" for="radioVisa">
                                <img src="{{ asset('images/paymentmethods/visa.png') }}"
                                     alt="Visa"
                                     class="payment-icon me-1">
                            </label>
                        </div>
                        <!-- Radio MasterCard -->
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="paymentMethod"
                                   id="radioMastercard"
                                   value="card"
                                   onclick="selecionarMetodoPagamento(this.value)">
                            <label class="form-check-label" for="radioMastercard">
                                <img src="{{ asset('images/paymentmethods/mastercard.png') }}"
                                     alt="MasterCard"
                                     class="payment-icon">
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <img src="{{ asset('images/icons/savemoney.png') }}"
                         alt="Economia"
                         class="payment-icon">
                    <p class="mt-2" style="color: #308227;">
                        Escolha Mbway e não pague a taxa de serviço!
                    </p>
                </div>

                <!-- Form de pagamento e dados do cliente -->
                <form method="POST" action="{{ route('payment.process') }}" onsubmit="return prepararCamposHidden(event)">
                    @csrf

                    @if (!Auth::check())
                        <div class="shadow-sm p-3 mb-4 bg-body rounded">
                            <h5 class="mb-2">Dados do cliente</h5>
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nome</label>
                                <input type="text"
                                       id="customer_name"
                                       name="customer_name"
                                       class="form-control"
                                       value="{{ old('customer_name') }}"
                                       autocomplete="name"
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="customer_email" class="form-label">E-mail</label>
                                <input type="email"
                                       id="customer_email"
                                       name="customer_email"
                                       class="form-control"
                                       value="{{ old('customer_email') }}"
                                       autocomplete="email"
                                       required>
                            </div>
                        </div>
                    @endif

                    <!-- Campo MBWay (exibido apenas quando selecionado) -->
                    <div id="mbway-phone-field" class="shadow-sm p-3 mb-4 bg-body rounded" style="display: none;">
                        <label for="mbway_phone" class="form-label">Telefone MBWay</label>
                        <input type="text"
                               id="mbway_phone"
                               name="mbway_phone"
                               class="form-control"
                               placeholder="ex: 912345678"
                               value="{{ old('mbway_phone') }}">
                    </div>

                    <!-- Resumo do pedido -->
                    <div class="shadow-sm p-3 mb-4 bg-body rounded">
                        <h5 class="mb-2">Resumo do pedido</h5>
                        @foreach ($cart as $id => $item)
                            <div class="d-flex justify-content-between">
                                <span>{{ $item['name'] }}</span>
                                <span>€ {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                        @endforeach

                        <!-- Linha da taxa de serviço com ID para manipular via JS -->
                        <div class="d-flex justify-content-between" id="service-fee-row">
                            <span>Taxa de serviço:</span>
                            <span>€ 0,50</span>
                        </div>
                    </div>

                    <!-- Início: Campo para Fatura -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="check-fatura" name="fatura" onclick="toggleNifField()">
                            <label class="form-check-label" for="check-fatura">
                                <strong>Deseja fatura?</strong>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="nif_field" style="display: none;">
                        <label for="nif" class="form-label">NIF</label>
                        <input type="text" id="nif" name="nif" class="form-control" placeholder="Digite o contribuinte" maxlength="10">
                    </div>
                    <!-- Fim: Campo para Fatura -->

                    <!-- Inputs hidden para note, pickup_time e quantity de cada item -->
                    <div id="hidden-fields-container"></div>

                    <!-- Hidden para armazenar o total final calculado (com ou sem taxa) -->
                    <input type="hidden" name="final_total" id="final_total" value="0.00">

                    <!-- Total e botão Pagar -->
                    <div class="shadow-sm p-3 rounded" style="background-color:rgba(90, 88, 88, 0.14);">
                        <div class="d-flex justify-content-between mb-3">
                            <strong>TOTAL</strong>
                            <span id="carrinho-total"><strong>€ 0.00</strong></span>
                        </div>
                        <button type="submit"
                                id="finalizar-btn"
                                class="btn btn-primary w-100"
                                disabled
                                style="opacity: 0.6; background-color: #FF452B;">
                            Pagar
                        </button>
                    </div>

                    <!-- Campo hidden para o método de pagamento -->
                    <input type="hidden" name="payment_method" id="payment_method" value="">
                </form>
            </div>
            <!-- FIM COLUNA DIREITA -->
        </div>
    @else
        <!-- Caso o carrinho esteja vazio -->
        <div class="shadow-lg p-4 mt-5 bg-body rounded text-center mx-auto" style="max-width: 350px; width: 100%;">
            <img src="/images/icons/emptycart.png" alt="carrinho vazio" class="img-fluid empty-cart-img" style="max-height: 140px; object-fit: contain;">
            <h5 class="mb-3 text-secondary">Por hora, o seu carrinho está vazio!</h5>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3" role="button" aria-label="Ver Refeições">Ver Refeições</a>
        </div>
    @endif
</div>

<x-footer />

@endsection

<style>
    .payment-icon {
        width: 80px;
        height: 40px;
        object-fit: contain;
        transition: transform 0.2s;
    }
    .payment-icon:hover {
        transform: scale(1.05);
    }
    .metodo-pg {
        cursor: pointer;
        padding: 5px;
        transition: border 0.2s;
    }
    .metodo-pg.selected {
        border: 2px solid #0d6efd;
        border-radius: 6px;
    }
    .strike-red {
        text-decoration: line-through;
        color: #dc3545;
    }
</style>

<!-- JS do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- JS Customizado -->
<script>
    //Lógica para inserir nif 
    function toggleNifField() {
        var checkbox = document.getElementById('check-fatura');
        var nifField = document.getElementById('nif_field');
        nifField.style.display = checkbox.checked ? 'block' : 'none';
    }

    // Variável global para armazenar a taxa de serviço
    let serviceFee = 0;

    function processarQuantidade(id, delta) {
        const campoQuant = document.getElementById('campo-quant-' + id);
        let quantidadeAtual = parseInt(campoQuant.value) || 1;
        const produtoDiv = document.getElementById('produto-' + id);
        const maxStock = parseInt(produtoDiv.getAttribute('data-stock')) || 0;

        quantidadeAtual += delta;
        if (quantidadeAtual < 1) quantidadeAtual = 1;
        if (quantidadeAtual > maxStock) quantidadeAtual = maxStock;

        campoQuant.value = quantidadeAtual;
        atualizarBotao(id, quantidadeAtual, maxStock);
        atualizarQuantidade(id, quantidadeAtual);
    }

    function atualizarBotao(id, quantidadeAtual, maxStock) {
        const btnPlus = document.getElementById('btn-plus-' + id);
        const btnMinus = document.getElementById('btn-minus-' + id);
        btnPlus.disabled = (quantidadeAtual >= maxStock);
        btnMinus.disabled = (quantidadeAtual <= 1);
    }

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

    function recalcularTotalCarrinho() {
        let totalCarrinho = 0;
        document.querySelectorAll('[id^="produto-"]').forEach(produto => {
            const preco = parseFloat(produto.getAttribute('data-preco'));
            const campoQuant = produto.querySelector('[id^="campo-quant-"]');
            const quantidade = parseInt(campoQuant.value) || 1;
            totalCarrinho += preco * quantidade;
        });

        totalCarrinho += serviceFee;
        document.getElementById('carrinho-total').textContent = '€ ' + totalCarrinho.toFixed(2);
        document.getElementById('final_total').value = totalCarrinho.toFixed(2);
    }

    function toggleNoteField(id) {
        const noteField = document.getElementById('noteField-' + id);
        noteField.style.display = (noteField.style.display === 'none') ? 'block' : 'none';
    }

    function selecionarMetodoPagamento(metodo) {
        document.getElementById('payment_method').value = metodo;
        document.getElementById('mbway-phone-field').style.display = (metodo === 'mbway') ? 'block' : 'none';

        if (metodo === 'card') {
            serviceFee = 0.50;
            document.getElementById('service-fee-row').classList.remove('strike-red');
        } else {
            serviceFee = 0;
            document.getElementById('service-fee-row').classList.add('strike-red');
        }

        recalcularTotalCarrinho();
        const btn = document.getElementById('finalizar-btn');
        btn.disabled = false;
        btn.style.opacity = 1;
    }

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

            const hiddenNote = document.createElement('input');
            hiddenNote.type = 'hidden';
            hiddenNote.name = `items[${itemId}][note]`;
            hiddenNote.value = note;
            container.appendChild(hiddenNote);

            const hiddenPickup = document.createElement('input');
            hiddenPickup.type = 'hidden';
            hiddenPickup.name = `items[${itemId}][pickup_time]`;
            hiddenPickup.value = pickupTime;
            container.appendChild(hiddenPickup);

            const hiddenQty = document.createElement('input');
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
        document.querySelectorAll('[id^="produto-"]').forEach(produto => {
            const id = produto.id.split('-')[1];
            const campoQuant = document.getElementById('campo-quant-' + id);
            const quantidade = parseInt(campoQuant.value) || 1;
            const maxStock = parseInt(produto.getAttribute('data-stock')) || 0;
            atualizarBotao(id, quantidade, maxStock);
        });
        recalcularTotalCarrinho();

        @if(old('payment_method') === 'mbway')
            document.getElementById('payment_method').value = 'mbway';
            document.getElementById('mbway-phone-field').style.display = 'block';
            document.getElementById('finalizar-btn').disabled = false;
            document.getElementById('finalizar-btn').style.opacity = 1;
            const radioMbway = document.getElementById('radioMbway');
            if (radioMbway) radioMbway.checked = true;
            serviceFee = 0;
            document.getElementById('service-fee-row').classList.add('strike-red');
            recalcularTotalCarrinho();
        @elseif(old('payment_method') === 'card')
            document.getElementById('payment_method').value = 'card';
            document.getElementById('finalizar-btn').disabled = false;
            document.getElementById('finalizar-btn').style.opacity = 1;
            const radioVisa = document.getElementById('radioVisa');
            if (radioVisa) radioVisa.checked = true;
            serviceFee = 0.50;
            document.getElementById('service-fee-row').classList.remove('strike-red');
            recalcularTotalCarrinho();
        @endif

        @if(!Auth::check())
            const inputName = document.getElementById('customer_name');
            const inputEmail = document.getElementById('customer_email');

            if (localStorage.getItem('customer_name')) {
                inputName.value = localStorage.getItem('customer_name');
            }
            if (localStorage.getItem('customer_email')) {
                inputEmail.value = localStorage.getItem('customer_email');
            }

            inputName.addEventListener('input', () => {
                localStorage.setItem('customer_name', inputName.value);
            });
            inputEmail.addEventListener('input', () => {
                localStorage.setItem('customer_email', inputEmail.value);
            });
        @endif

        let cartSize = {{ $countItems }};
        if (cartSize > 1) {
            const allSelects = document.querySelectorAll('select[id^="horarios-"]');
            allSelects.forEach(select => {
                select.addEventListener('change', () => {
                    if (select.value) {
                        const userConfirmed = confirm("Deseja selecionar o mesmo horário para todos os pedidos?");
                        if (userConfirmed) {
                            const selectedValue = select.value;
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
