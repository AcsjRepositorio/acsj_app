@extends('adminlte::page')

@section('title', 'Consultar Pedidos')

@section('content_header')
    <h1>Dashboard - Consulta de Pedidos</h1>
@stop

@section('content')
<div class="container mt-5">

    {{-- Mensagens de Sucesso ou Erro --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Formulário para enviar as alterações --}}
    <form action="{{ route('adminpanel.manage.order.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Verifica se existe algum dado no array agrupado --}}
        @forelse($groupedData as $date => $horarios)

        @php
            // Ajuste: Cria uma instância do Carbon a partir do formato 'd/m/Y'
            $carbonDate = \Carbon\Carbon::createFromFormat('d/m/Y', $date)->locale('pt_PT');
            // Formata o dia da semana
            $dayOfWeek = $carbonDate->isoFormat('dddd');
            // Opcional: Capitaliza a primeira letra do dia da semana
            $dayOfWeek = ucfirst($dayOfWeek);
        @endphp
        
            <!-- Título para cada data -->
            <h3 class="mt-4">Data: {{ $carbonDate->format('d/m/Y') }}  ({{ $dayOfWeek }})</h3>

            {{-- Para cada horário dentro da data --}}
            @foreach($horarios as $pickupTime => $itemsNesteHorario)
                <h5 class="mt-3">Horário: {{ $pickupTime }}  </h5>

                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Nome do Cliente</th>
                            <th>Email do Cliente</th>
                            <th>Prato</th>
                            <th>Quantidade</th>
                            <th>Observações</th>
                            <th>Pickup Time</th>
                            <th>Disponível para Preparo</th>
                            <th>Entregue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($itemsNesteHorario as $item)
                            <tr class="{{ $item['entregue'] ? 'entregue-sim' : '' }}">
                                <td>{{ $item['order_id'] }}</td>
                                <td>{{ $item['customer_name'] }}</td>
                                <td>{{ $item['customer_email'] }}</td>
                                <td>{{ $item['meal_name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>{{ $item['note'] }}</td>
                                <td>{{ $item['pickup_time'] }}</td>

                                <!-- Seletor "Disponível para Preparo" -->
                                <td>
                                    <select name="disponivel_preparo[{{ $item['order_meal_id'] }}]" class="form-control">
                                        <option value="sim" {{ $item['disponivel_preparo'] ? 'selected' : '' }}>Sim</option>
                                        <option value="nao" {{ !$item['disponivel_preparo'] ? 'selected' : '' }}>Não</option>
                                    </select>
                                </td>

                                <!-- Seletor "Entregue" -->
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

        <!-- Botão para salvar as alterações -->
        <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
    </form>
</div>
@stop

@section('css')
<style>
    /* Cor padrão para linhas entregues */
    .entregue-sim {
        background-color: #d4edda !important; /* Verde claro */
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet" 
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
      crossorigin="anonymous">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Seleciona todos os campos do select "Entregue"
        const entregueSelects = document.querySelectorAll('.entregue-select');

        // Adiciona evento change para cada select
        entregueSelects.forEach(select => {
            select.addEventListener('change', function () {
                const row = select.closest('tr'); // Pega a linha da tabela correspondente
                if (this.value === 'sim') {
                    // Adiciona a classe para alterar a cor
                    row.classList.add('entregue-sim');
                } else {
                    // Remove a classe
                    row.classList.remove('entregue-sim');
                }
            });

            // Inicializa a cor da linha com base no valor atual do select
            if (select.value === 'sim') {
                select.closest('tr').classList.add('entregue-sim');
            }
        });
    });
</script>
@stop
