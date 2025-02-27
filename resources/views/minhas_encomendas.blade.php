@extends('layouts.masterlayout') 

@section('content')
<x-navbar />
<x-cart />

<div class="container my-4">
    <h1 class="mb-4">Minhas Encomendas</h1>

    @if($orders->isEmpty())
        <p>Você ainda não possui encomendas.</p>
    @else
        <div class="row">
            @foreach($orders as $order)
                @if($order->status == 'completed')
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header  text-white d-flex justify-content-between align-items-center" style="background-color: #156064;">
                                <h5 class="card-title mb-0">Ordem: {{ $order->order_id }}</h5>
                                <!-- Botão de Exclusão -->
                                <button class="btn  btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $order->order_id }}" style="background-color:rgba(201, 201, 201, 0.17);">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Informações básicas do pedido -->
                                <p><strong>Valor:</strong> {{ $order->amount }} {{ $order->currency }}</p>
                                <p><strong>Status:</strong> {{ $order->status }}</p>
                                <p><strong>Método de Pagamento:</strong> {{ $order->payment_method }}</p>
                                <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

                                <!-- Botões para alternar detalhes -->
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm open-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->order_id }}" aria-expanded="false" aria-controls="collapse{{ $order->order_id }}">
                                        <i class="bi bi-plus"></i> 
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm close-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->order_id }}" aria-expanded="true" aria-controls="collapse{{ $order->order_id }}" style="display: none;">
                                        <i class="bi bi-dash"></i> Fechar
                                    </button>
                                </div>

                                <!-- Área de detalhes ocultos -->
                                <div class="collapse mt-3" id="collapse{{ $order->order_id }}">
                                    <hr>
                                    <h6>Detalhes dos Refeições</h6>
                                    @if($order->meals->isNotEmpty())
                                        <ul class="list-unstyled">
                                            @foreach($order->meals as $meal)
                                                <li class="mb-2">
                                                    <strong>{{ $meal->name ?? 'Nome da refeição' }}</strong><br>
                                                    <small>
                                                        Quantidade: {{ $meal->pivot->quantity }}<br>
                                                        Dia: {{ $meal->pivot->day_of_week }}<br>
                                                        Hora de retirada: {{ $meal->pivot->pickup_time }}<br>
                                                        @if($meal->pivot->note)
                                                            Observação: {{ $meal->pivot->note }}
                                                        @endif
                                                    </small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Sem refeições associadas.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal de Confirmação para Exclusão -->
                    <div class="modal fade" id="deleteModal{{ $order->order_id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $order->order_id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $order->order_id }}">Confirmar Exclusão</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                </div>
                                <div class="modal-body">
                                    Tem certeza de que deseja excluir a ordem {{ $order->order_id }}?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Sim, excluir</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>

<x-footer />

@push('scripts')





 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Seleciona todos os botões de abrir e fechar
            var openButtons = document.querySelectorAll('.open-btn');
            var closeButtons = document.querySelectorAll('.close-btn');

            openButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    // Quando abrir, esconde o botão de abrir e mostra o de fechar
                    var target = btn.getAttribute('data-bs-target');
                    var closeBtn = document.querySelector(target).parentElement.querySelector('.close-btn');
                    btn.style.display = 'none';
                    closeBtn.style.display = 'inline-block';
                });
            });

            closeButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    // Quando fechar, esconde o botão de fechar e mostra o de abrir
                    var target = btn.getAttribute('data-bs-target');
                    var openBtn = document.querySelector(target).parentElement.querySelector('.open-btn');
                    btn.style.display = 'none';
                    openBtn.style.display = 'inline-block';
                });
            });

            // Também é possível usar os eventos do collapse do Bootstrap para sincronizar:
            var collapses = document.querySelectorAll('.collapse');
            collapses.forEach(function (collapseEl) {
                collapseEl.addEventListener('hidden.bs.collapse', function () {
                    var parent = collapseEl.parentElement;
                    var openBtn = parent.querySelector('.open-btn');
                    var closeBtn = parent.querySelector('.close-btn');
                    openBtn.style.display = 'inline-block';
                    closeBtn.style.display = 'none';
                });
                collapseEl.addEventListener('shown.bs.collapse', function () {
                    var parent = collapseEl.parentElement;
                    var openBtn = parent.querySelector('.open-btn');
                    var closeBtn = parent.querySelector('.close-btn');
                    openBtn.style.display = 'none';
                    closeBtn.style.display = 'inline-block';
                });
            });
        });
    </script>
@endpush
@endsection

