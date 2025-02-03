@extends('adminlte::page')

@section('title', 'Consultar Pedidos')

@section('content_header')
<h1>Dashboard - Consulta de Pedidos</h1>
@stop

@section('content')
<div class="container mt-5">

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

<!-- Bloco sombreado centralizado -->
<div class="d-flex justify-content-center">
    <div class="shadow p-3 bg-body rounded" style="max-width: 800px; width: 100%;">
        
        <!-- FORMULÁRIO DE BUSCA (CENTRALIZADO) -->
        <form action="{{ route('adminpanel.manage.order.search') }}" method="GET" id="searchForm" class="mb-3">
            <div class="input-group">
                <input type="text"
                    id="searchQuery"
                    name="query"
                    class="form-control form-control-sm"
                    placeholder="Busque por pedido, nome do cliente"
                    value="{{ request('query') }}">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
        <!-- FIM DO FORMULÁRIO DE BUSCA -->

        <!-- FORMULÁRIO DE FILTRO -->
        <form action="{{ route('adminpanel.manage.order') }}" method="GET" id="filterForm">
            <div class="row align-items-end">
                <!-- Data (Com Ícone de Calendário) --> 
                <div class="col-md-3">
                    <label for="selectedDate"><strong>Data:</strong></label>
                    <div class="input-group">
                        <input type="text"
                            id="selectedDate"
                            name="selectedDate"
                            class="form-control form-control-sm"
                            value="{{ request('selectedDate') }}"
                            autocomplete="off">
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                    </div>
                </div>

                <!-- Filtro Adicional -->
                <div class="col-md-2">
                    <label for="additionalFilter"><strong>Filtro Adicional:</strong></label>
                    <select name="additional_filter" id="additionalFilter" class="form-control form-control-sm w-100">
                        <option value="">Selecione...</option>
                        <option value="horario" {{ request('additional_filter') == 'horario' ? 'selected' : '' }}>Horário de Pickup</option>
                        <option value="disponivel" {{ request('additional_filter') == 'disponivel' ? 'selected' : '' }}>Disponível para Preparo</option>
                        <option value="entregue" {{ request('additional_filter') == 'entregue' ? 'selected' : '' }}>Entregue</option>
                    </select>
                </div>

                <!-- Janela de Horário (Com Ícone de Relógio) -->
                <div class="col-md-2" id="pickupWindowContainer" style="display: none;">
                    <label for="pickupWindow"><strong>Janela de Horário:</strong></label>
                    <div class="input-group">
                        <select name="pickup_window" id="pickupWindow" class="form-control form-control-sm">
                            <option value="">Selecione...</option>
                            <option value="12h15 - 12h30" {{ request('pickup_window') === '12h15 - 12h30' ? 'selected' : '' }}>12h15 - 12h30</option>
                            <option value="12h30 - 13h00" {{ request('pickup_window') === '12h30 - 13h00' ? 'selected' : '' }}>12h30 - 13h00</option>
                            <option value="13h00 - 13h30" {{ request('pickup_window') === '13h00 - 13h30' ? 'selected' : '' }}>13h00 - 13h30</option>
                            <option value="13h30 - 14h00" {{ request('pickup_window') === '13h30 - 14h00' ? 'selected' : '' }}>13h30 - 14h00</option>
                        </select>
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-clock"></i>
                        </span>
                    </div>
                </div>

                <!-- Botão Filtrar (Agora no final da linha) -->
                <div class="col-md-3 text-end">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>
            </div>
        </form>
        <!-- FIM DO FORMULÁRIO DE FILTRO -->

    </div>
</div>



        






    @if(!empty($groupedData))
    <form action="{{ route('adminpanel.manage.order.update') }}" method="POST">
        @csrf
        @method('PUT')

        @forelse($groupedData as $rawDate => $horarios)
        @php
        try {
        $carbonDate = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->locale('pt_PT');
        $formatada = $carbonDate->format('d/m/Y');
        $diaDaSemana = ucfirst($carbonDate->isoFormat('dddd'));
        } catch (\Exception $e) {
        $formatada = $rawDate;
        $diaDaSemana = '';
        }
        @endphp

        <h3 class="mt-4">Data: {{ $formatada }} ({{ $diaDaSemana }})</h3>

        @foreach($horarios as $pickupTime => $items)
        <h5 class="mt-3">Horário: {{ $pickupTime }}</h5>
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Nome do Cliente</th>
                    <th>Email</th>
                    <th>Prato</th>
                    <th>Qtd</th>
                    <th>Obs</th>
                    <th>Pickup Time</th>
                    <th>Disp. Preparo</th>
                    <th>Entregue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr class="{{ $item['entregue'] ? 'entregue-sim' : '' }}">
                    <td>{{ $item['order_id'] }}</td>
                    <td>{{ $item['customer_name'] }}</td>
                    <td>{{ $item['customer_email'] }}</td>
                    <td>{{ $item['meal_name'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['note'] }}</td>
                    <td>{{ $item['pickup_time'] }}</td>
                    <td>
                        <select name="disponivel_preparo[{{ $item['order_meal_id'] }}]" class="form-control">
                            <option value="sim" {{ $item['disponivel_preparo'] ? 'selected' : '' }}>Sim</option>
                            <option value="nao" {{ !$item['disponivel_preparo'] ? 'selected' : '' }}>Não</option>
                        </select>
                    </td>
                    <td>
                        <select name="entregue[{{ $item['order_meal_id'] }}]" class="form-control entregue-select">
                            <option value="sim" {{ $item['entregue'] ? 'selected' : '' }}>Sim</option>
                            <option value="nao" {{ !$item['entregue'] ? 'selected' : '' }}>Não</option>
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
        @empty
        <p>Nenhum pedido encontrado.</p>
        @endforelse

        <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
    </form>
    @endif
</div>
@stop

@section('css')
<!-- Bootstrap 5 + Datepicker CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
<!-- Font Awesome para os ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .entregue-sim {
        background-color: #d4edda !important;
        /* verde claro */
    }
</style>
@stop

@section('js')
<!-- Bootstrap 5 + Datepicker JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.pt-BR.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o Datepicker
        $('#selectedDate').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR',
            autoclose: true,
            todayHighlight: true
        });

        // Ao mudar o filtro adicional, se "horario" for escolhido, exibe o select de janela de horário;
        // Se não houver data selecionada, alerta e reseta.
        $('#additionalFilter').on('change', function() {
            var selectedVal = $(this).val();
            var dateVal = $('#selectedDate').val();
            if (selectedVal === 'horario') {
                if (!dateVal) {
                    alert("Por favor, selecione uma data antes de escolher 'Horário de Pickup'.");
                    $(this).val('');
                    $('#pickupWindowContainer').hide();
                } else {
                    $('#pickupWindowContainer').show();
                }
            } else {
                $('#pickupWindowContainer').hide();
            }
        });

        // Se a página for recarregada e o filtro adicional for "horario", mostra o container (caso já tenha valor)
        if ($('#additionalFilter').val() === 'horario') {
            $('#pickupWindowContainer').show();
        }

        // Exemplo: colorir linhas onde "Entregue" = sim
        const entregueSelects = document.querySelectorAll('.entregue-select');
        entregueSelects.forEach(select => {
            const row = select.closest('tr');
            if (select.value === 'sim') {
                row.classList.add('entregue-sim');
            }
            select.addEventListener('change', () => {
                if (select.value === 'sim') {
                    row.classList.add('entregue-sim');
                } else {
                    row.classList.remove('entregue-sim');
                }
            });
        });
    });
</script>
@stop