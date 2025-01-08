<!-- Usei o  OFFCANVAS (SIDEBAR)   -->
<div class="offcanvas offcanvas-end" 
     tabindex="-1" 
     id="offcanvasCart" 
     aria-labelledby="offcanvasCartLabel">

    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasCartLabel">Seu Carrinho</h5>
        <button type="button" class="btn-close text-reset" 
                data-bs-dismiss="offcanvas" 
                aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        
        <!-- código do carrinho  -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (is_array($cart) && count($cart) > 0)
            <table class="table">
                <tbody>
                    @foreach ($cart as $id => $item)
                        <tr>
                            <td>
                                <img src="{{ $item['photo'] 
                                    ? asset('storage/' . $item['photo']) 
                                    : asset('images/default-meal.jpg') }}"
                                    alt="{{ $item['name'] }}"
                                    width="80">
                            </td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>€{{ number_format($item['price'], 2) }}</td>
                            <td>€{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <form method="POST" action="{{ route('cart.destroy', $id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf
                <button type="submit" class="btn btn-warning">Limpar Carrinho</button>
            </form>
        @else
            <p>O carrinho está vazio.</p>
        @endif
       

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

