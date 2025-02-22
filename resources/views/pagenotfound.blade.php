
<x-navbar />


    <title>Erro 404 - Página Não Encontrada</title>


    <div class="error-container text-center mt-5 mb-5">
        <!-- Exemplo de uso da imagem -->
        <img src="{{ asset('images/icons/error404.png') }}" alt="Erro 404">
        <h1>Página não encontrada</h1>
        <p>Desculpe, mas a página que você está procurando não existe ou foi removida.</p>
        <a  href="{{ url('/') }}" class=" btn-primary">Voltar para a Home</a>
    </div>

<x-cart />
<x-footer />

<link> 


</link>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>