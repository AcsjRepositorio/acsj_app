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
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Ordem: {{ $order->order_id }}</h5>
                            </div>
                            <div class="card-body">
                                <!-- Informações básicas do pedido -->
                                <p><strong>Valor:</strong> {{ $order->amount }} {{ $order->currency }}</p>
                                <p><strong>Status:</strong> {{ $order->status }}</p>
                                <p><strong>Método de Pagamento:</strong> {{ $order->payment_method }}</p>
                                <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

                                <!-- Botão para expandir detalhes -->
                                <button class="btn btn-outline-primary btn-sm toggle-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->order_id }}" aria-expanded="false" aria-controls="collapse{{ $order->order_id }}">
                                    <i class="bi bi-plus"></i>
                                </button>

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
                @endif
            @endforeach
        </div>
    @endif
</div>

<x-footer />

@push('scripts')
    
    
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var toggles = document.querySelectorAll('.toggle-btn');
        toggles.forEach(function (btn) {
            var targetSelector = btn.getAttribute('data-bs-target');
            var collapseElement = document.querySelector(targetSelector);
            
            // Verifica se o elemento de collapse existe
            if (!collapseElement) return;

            collapseElement.addEventListener('show.bs.collapse', function () {
                btn.innerHTML = '<i class="bi bi-dash"></i>';
            });

            collapseElement.addEventListener('hide.bs.collapse', function () {
                btn.innerHTML = '<i class="bi bi-plus"></i>';
            });
        });
    });
    </script>
@endpush
