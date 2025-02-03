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

    <!-- (NOVO) FORMULÁRIO DE FILTRO POR DATA -->
    <form action="{{ route('adminpanel.manage.order') }}" method="GET" class="row mb-4" id="filterForm">
        <div class="col-sm-3">
            <label for="selectedDate">Selecione a Data:</label>
            <input type="text"
                   id="selectedDate"
                   name="selectedDate"
                   class="form-control"
                   value="{{ request('selectedDate') }}"  
                   autocomplete="off"> <!-- Mantém a data no input após filtrar -->
        </div>
        <div class="col-sm-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>
    <!-- FIM DO FORMULÁRIO DE FILTRO -->

    @if(!empty($groupedData))
    <form action="{{ route('adminpanel.manage.order.update') }}" method="POST">
        @csrf
        @method('PUT')

        @forelse($groupedData as $rawDate => $horarios)
            @php
                try {
                    $carbonDate  = \Carbon\Carbon::createFromFormat('d/m/Y', $rawDate)->locale('pt_PT');
                    $formatada   = $carbonDate->format('d/m/Y'); 
                    $diaDaSemana = ucfirst($carbonDate->isoFormat('dddd'));
                } catch (\Exception $e) {
                    $formatada   = $rawDate;
                    $diaDaSemana = '';
                }
            @endphp

            <h3 class="mt-4">
                Data: {{ $formatada }} ({{ $diaDaSemana }})
            </h3>

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

<style>
    .entregue-sim {
        background-color: #d4edda !important; /* verde claro */
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
    // Inicializa o Datepicker para dd/mm/yyyy
    $('#selectedDate').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        autoclose: true,
        todayHighlight: true
    });

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
