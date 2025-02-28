<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    @include('profile.partials.favicons')

    <style>
        /* Ajuste o valor conforme a altura do seu navbar fixo */
        body {
            padding-top: 200px;
        }
    </style>
</head>
<body>
    @yield('content')

    @include('cookie-consent')
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cookieButton = document.getElementById('cookie-consent-button');
            if (cookieButton) {
                cookieButton.addEventListener('click', function() {
                    // Faz uma requisição para a rota que define o cookie
                    fetch('{{ route("accept-cookie") }}', { 
                        method: 'POST', 
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        // Ao receber sucesso, esconde o banner
                        document.getElementById('cookie-consent-banner').style.display = 'none';
                    });
                });
            }
        });
    </script>
</body>
</html>
