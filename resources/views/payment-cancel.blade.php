<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pagamento Cancelado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Pagamento Cancelado</h1>
        <p>O seu pagamento foi cancelado. Por favor, tente novamente.</p>
        <a href="{{ route('checkout') }}" class="btn btn-warning">Tentar Novamente</a>
    </div>
</body>
</html>

