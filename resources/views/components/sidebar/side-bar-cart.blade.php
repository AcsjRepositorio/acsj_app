<div class="offcanvas offcanvas-start" tabindex="-1" id="sideBarCart" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Seu carrinho</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @if (!empty($cartItems) && $cartItems->count() > 0)
            <ul class="list-group">
                @foreach ($cartItems as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="{{ $item->meal->photo }}" alt="{{ $item->meal->name }}" width="50">
                            {{ $item->meal->name }} (x{{ $item->quantity }})
                        </div>
                        <span>€{{ number_format($item->meal->price * $item->quantity, 2) }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Seu carrinho está vazio.</p>
        @endif
    </div>
</div>

