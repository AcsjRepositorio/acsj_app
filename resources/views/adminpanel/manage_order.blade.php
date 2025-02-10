@extends('adminlte::page')

@section('title', 'Consultar Pedidos')

@section('content_header')
    <h1>Dashboard - Consulta de Pedidos</h1>
@stop

@section('content')
<div class="container mt-8">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Bloco sombreado centralizado -->
    <div class="d-flex justify-content-center">
        <div class="shadow p-3 bg-body rounded" style="max-width: 800px; width: 100%;">
            <div class="d-flex justify-content-center">
                <form action="{{ route('adminpanel.manage.order.search') }}" method="GET" id="searchForm" class="mb-3 w-100">
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
            </div>

            <!-- Div do filtro -->
            <div class="d-flex justify-content-center align-items-center">
                <form action="{{ route('adminpanel.manage.order') }}" method="GET" id="filterForm" class="d-flex flex-row gap-2">
                    <div class="input-group">
                        <input type="text"
                               placeholder="selecione uma data"
                               id="selectedDate"
                               name="selectedDate"
                               class="form-control form-control-sm"
                               value="{{ request('selectedDate') }}"
                               autocomplete="off">
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-calendar-days"></i>
                        </span>
                    </div>

                    <div class="input-group">
                        <select name="additional_filter" id="additionalFilter" class="form-control form-control-sm w-100">
                            <option value="">Escolha uma categoria</option>
                            <option value="horario" {{ request('additional_filter') == 'horario' ? 'selected' : '' }}>Horário de Pickup</option>
                            <option value="disponivel" {{ request('additional_filter') == 'disponivel' ? 'selected' : '' }}>Disponível para Preparo</option>
                            <option value="entregue" {{ request('additional_filter') == 'entregue' ? 'selected' : '' }}>Entregue</option>
                        </select>
                    </div>

                    <div class="input-group" id="pickupWindowContainer" style="display: none;">
                        <select name="pickup_window" id="pickupWindow" class="form-control form-control-sm">
                            <option value="">Horários...</option>
                            <option value="12h15 - 12h30" {{ request('pickup_window') === '12h15 - 12h30' ? 'selected' : '' }}>12h15 - 12h30</option>
                            <option value="12h30 - 13h00" {{ request('pickup_window') === '12h30 - 13h00' ? 'selected' : '' }}>12h30 - 13h00</option>
                            <option value="13h00 - 13h30" {{ request('pickup_window') === '13h00 - 13h30' ? 'selected' : '' }}>13h00 - 13h30</option>
                            <option value="13h30 - 14h00" {{ request('pickup_window') === '13h30 - 14h00' ? 'selected' : '' }}>13h30 - 14h00</option>
                        </select>
                        <span class="input-group-text bg-white">
                            <i class="fa-solid fa-clock"></i>
                        </span>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Botão de Limpar Filtros -->
            <div class="col-md-12 text-start mt-2">
                <a href="{{ route('adminpanel.manage.order') }}" class="btn btn-danger">
                    Limpar filtros
                </a>
            </div>
        </div>
    </div>

    <!-- Cada botão atualiza via AJAX -->
    @if(!empty($groupedData))
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
                            <th style="width: 120px;">ENTREGUE</th>
                            <th style="width: 130px;">DISP.Preparo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item['order_id'] }}</td>
                                <td>{{ $item['customer_name'] }}</td>
                                <td>{{ $item['customer_email'] }}</td>
                                <td>{{ $item['meal_name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ $item['note'] }}</td>
                                <td>{{ $item['pickup_time'] }}</td>
                                <!-- ENTREGUE (AJAX com botão) -->
                                <td>
                                    <button type="button"
                                        class="btn btn-sm ajax-toggle entregue-btn {{ $item['entregue'] ? 'btn-success' : 'btn-outline-dark' }}"
                                        data-pivot-id="{{ $item['order_meal_id'] }}"
                                        data-field="entregue"
                                        data-value="{{ $item['entregue'] ? 'sim' : 'nao' }}">
                                        {{ $item['entregue'] ? 'Sim' : 'Não' }}
                                    </button>
                                </td>
                                <!-- DISP. PREPARO (AJAX com botão) -->
                                <td class="disp-prep">
                                    <button type="button"
                                        class="btn btn-sm ajax-toggle preparo-btn {{ $item['disponivel_preparo'] ? 'btn-warning' : 'btn-outline-dark' }}"
                                        data-pivot-id="{{ $item['order_meal_id'] }}"
                                        data-field="disponivel_preparo"
                                        data-value="{{ $item['disponivel_preparo'] ? 'sim' : 'nao' }}">
                                        {{ $item['disponivel_preparo'] ? 'Sim' : 'Não' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @empty
            <p>Nenhum pedido encontrado.</p>
        @endforelse
    @else
        <div class="shadow p-4 mt-5 bg-body rounded text-center mx-auto" style="max-width: 300px; width: 100%;">
            <img src="/images/icons/emptyfolder.png" alt="Empty Folder" class="img-fluid" style="max-height: 120px; object-fit: contain;">
            <p class="mt-2">A busca não retornou resultados</p>
        </div>
    @endif
</div>
@stop

@section('css')
    <!-- Estilos e dependências -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Disponível Preparo: âmbar (gradient) */
        .preparo-sim {
            background: linear-gradient(135deg, #fff3cd, #ffe8a1) !important;
        }
        /* Entregue: verde (gradient) */
        .entregue-sim {
            background: linear-gradient(135deg,rgb(63, 200, 95), #c3e6cb) !important;
        }
    </style>
@stop

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <!-- Datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/locales/bootstrap-datepicker.pt-BR.min.js"></script>

    <script>
        // Função para atualizar as cores visuais da linha/célula
        function updateRowColor($row) {
            var entregueVal = $row.find('[data-field="entregue"]').data('value');
            // Remove classes de cores anteriores
            $row.removeClass('entregue-sim');
            $row.find('td.disp-prep').removeClass('preparo-sim');

            if (entregueVal === 'sim') {
                // Se entregue for sim, pinta a linha inteira de verde
                $row.addClass('entregue-sim');
            } else {
                // Se não entregue e disponível para preparo for sim, pinta somente a célula de âmbar
                var prepVal = $row.find('[data-field="disponivel_preparo"]').data('value');
                if (prepVal === 'sim') {
                    $row.find('[data-field="disponivel_preparo"]').closest('td').addClass('preparo-sim');
                }
            }
        }

        $(document).ready(function() {
            // Inicializa o Datepicker
            $('#selectedDate').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                autoclose: true,
                todayHighlight: true
            });

            // Filtro de horário
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

            if ($('#additionalFilter').val() === 'horario') {
                $('#pickupWindowContainer').show();
            }

            // Ao carregar a página, atualiza as cores de cada linha
            $('table tbody tr').each(function() {
                updateRowColor($(this));
            });

            // Ao clicar nos botões (ENTREGUE e DISP. PREPARO)
            $('.ajax-toggle').on('click', function() {
                var $btn = $(this);
                var pivotId = $btn.data('pivot-id');
                var field = $btn.data('field'); // 'entregue' ou 'disponivel_preparo'
                var currentValue = $btn.data('value'); // 'sim' ou 'nao'
                var newValue = (currentValue === 'sim') ? 'nao' : 'sim';

                // Chama a rota de updateField via AJAX (PATCH)
                $.ajax({
                    url: '{{ route("adminpanel.manage.order.updateField") }}',
                    method: 'PATCH',
                    data: {
                        pivot_id: pivotId,
                        field: field,
                        value: newValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Atualiza o valor e o texto do botão
                        $btn.data('value', newValue);
                        $btn.text(newValue === 'sim' ? 'Sim' : 'Não');

                        // Atualiza a classe do botão conforme o campo
                        if(field === 'entregue') {
                            if(newValue === 'sim') {
                                $btn.removeClass('btn-outline-success').addClass('btn-success');
                            } else {
                                $btn.removeClass('btn-success').addClass('btn-outline-success');
                            }
                        } else if(field === 'disponivel_preparo') {
                            if(newValue === 'sim') {
                                $btn.removeClass('btn-outline-warning').addClass('btn-warning');
                            } else {
                                $btn.removeClass('btn-warning').addClass('btn-outline-warning');
                            }
                        }

                        // Atualiza a cor visual da linha ou célula
                        updateRowColor($btn.closest('tr'));
                    },
                    error: function(xhr) {
                        alert('Erro ao atualizar: ' + (xhr.responseJSON?.error || xhr.statusText));
                    }
                });
            });
        });
    </script>
@stop
