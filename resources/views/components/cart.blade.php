<div class="container">
    <h1>Seu Carrinho</h1>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (is_array($cart) && count($cart) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $id => $item)
                    <tr>
                        <td>
                            <img src="{{ $item['photo'] ? asset('storage/' . $item['photo']) : asset('images/default-meal.jpg') }}" alt="{{ $item['name'] }}" width="80">
                        </td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>€{{ number_format($item['price'], 2) }}</td>
                        <td>€{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                        <td>
                            <form method="POST" action="{{ route('cart.destroy', $id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remover</button>
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
