@extends('adminlte::page')

@section('title', 'Pedidos - Visão Geral')

@section('content_header')
<p>Dashboard</p>
@stop

@section('content')
<h1>Pedidos - Visão Geral </h1>

<!-- Search Bar -->
<div class="d-flex justify-content-center mt-5 mb-3">
    <div class="shadow p-3 bg-body rounded" style="max-width: 800px; width: 100%;">
        <div class="d-flex justify-content-center">
            <form action="{{ route('adminpanel.manage.order.overview.search') }}" method="GET" class="mb-3 mt-3" style="max-width: 800px; width: 100%;">
                <div class="input-group">
                    <input type="text" name="query" class="form-control form-control-sm" placeholder="Buscar por pedido ou nome do cliente" value="{{ request('query') }}">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Filter Form -->
        <div class="d-flex justify-content-center">
            <form action="{{ route('adminpanel.manage.order.overview.filter') }}" method="GET" class="mb-3" style="max-width: 800px; width: 100%;">
                <div class="row g-2">
                    <div class="col-md-3">
                        <div class="input-group input-group-sm">
                            <input type="date" name="selectedDate" class="form-control" value="{{ request('selectedDate') }}" placeholder="Data do pedido">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="payment_status" class="form-select form-select-sm">
                            <option value="">Status do pagamento</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Pago</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="payment_method" class="form-select form-select-sm">
                            <option value="">Método de pagamento</option>
                            <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Cartão</option>
                            <option value="mbway" {{ request('payment_method') == 'mbway' ? 'selected' : '' }}>Mbway</option>
                            <option value="multibanco" {{ request('payment_method') == 'multibanco' ? 'selected' : '' }}>Multibanco</option>
                        </select>
                    </div>
                    <div class="col-md-1 text-end">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                    <div class="col-md-12 text-start mt-3">
                        <a href="{{ route('adminpanel.order.overview') }}" class="btn btn-danger btn-sm">
                            Limpar filtros
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Orders Table with Delete Form -->
<form action="{{ route('adminpanel.order.delete') }}" method="POST" id="deleteForm">
    @csrf
    @method('DELETE')
    <div class="container mt-3">
        @if(isset($orders) && $orders->count() > 0)
            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        
                        <th>Nº do pedido</th>
                        <th>Características do pedido</th>
                        <th>Data do pedido</th>
                        <th>Valor total</th>
                        <th>Status do pagamento</th>
                        <th>Método de pagamento</th>
                        <th>Cliente</th>
                        <th>Selecionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        
                        <td>{{ $order->order_id }}</td>
                        <td style="background-color:rgba(102, 56, 115, 0.23)">
                            @foreach($order->meals as $meal)
                            <div class="accordion" id="mealAccordion{{ $meal->id }}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading{{ $meal->id }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $meal->id }}" aria-expanded="false" aria-controls="collapse{{ $meal->id }}">
                                            Detalhes do Prato: {{ $meal->name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $meal->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $meal->id }}" data-bs-parent="#mealAccordion{{ $meal->id }}">
                                        <div class="accordion-body" style="background-color: #ffff; padding: 15px; border-radius: 8px;">
                                            <div class="row mb-3">
                                                <div class="col-md-6"><strong>Prato:</strong></div>
                                                <div class="col-md-6">{{ $meal->name }}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6"><strong>Quantidade:</strong></div>
                                                <div class="col-md-6">{{ $meal->pivot->quantity }}</div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6"><strong>Agendado:</strong></div>
                                                <div class="col-md-6">{{ $meal->day_week_start }} - {{ $meal->day_of_week }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>€ {{ number_format($order->amount, 2, ',', '.') }}</td>
                        <td>{{ ucfirst($order->payment_status) }}</td>
                        <td>{{ ucfirst($order->payment_method) }}</td>
                        <td>
                            <div><strong>Nome:</strong> {{ $order->customer_name }}</div>
                            <div><strong>Email:</strong> {{ $order->customer_email }}</div>
                            <div><strong>Nif:</strong> <b>{{ $order->nif }}</b></div>
                        </td>
                        <td>
                            <input type="checkbox" name="order_ids[]" value="{{ $order->id }}">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginação -->
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            <!-- Botão de apagar selecionados -->
            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja apagar os pedidos selecionados?')">
                    Apagar selecionados
                </button>
            </div>
        @else
            <div class="shadow p-4 mt-5 bg-body rounded text-center mx-auto" style="max-width: 300px; width: 100%;">
                <img src="/images/icons/emptyfolder.png" alt="Empty Folder" class="img-fluid" style="max-height: 120px; object-fit: contain;">
                <p class="mt-2">A busca não retornou resultados</p>
            </div>
        @endif
    </div>
</form>

@stop

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
@stop
