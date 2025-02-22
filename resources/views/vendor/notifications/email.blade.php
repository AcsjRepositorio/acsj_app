<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
    <style>
        /* Estilos inline para compatibilidade com e-mail */
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            background: #ffffff;
            padding: 20px;
            margin: auto;
            max-width: 600px;
            border: 1px solid #dddddd;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: #3490dc;
            color: #ffffff !important;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .content p {
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Cabeçalho com a Logo personalizada -->
    <div class="header">
    <a href="{{ config('app.url') }}">
    <img src="{{ asset('images/acsj_logo.png') }}" alt="Associação das Crianças do Hospital São João" style="height: 50px;">
</a>
    </div>

    <!-- Saudação -->
    <div class="content">
        @if (! empty($greeting))
            <h1>{{ $greeting }}</h1>
        @else
            @if ($level === 'error')
                <h1>@lang('Whoops!')</h1>
            @else
                <h1>@lang('Hello!')</h1>
            @endif
        @endif

        <!-- Linhas de Introdução -->
        @foreach ($introLines as $line)
            <p>{{ $line }}</p>
        @endforeach

        <!-- Botão de Ação -->
        @isset($actionText)
            <p style="text-align: center;">
                <a href="{{ $actionUrl }}" class="button">
                    {{ $actionText }}
                </a>
            </p>
        @endisset

        <!-- Linhas Finais -->
        @foreach ($outroLines as $line)
            <p>{{ $line }}</p>
        @endforeach

        <!-- Despedida -->
        @if (! empty($salutation))
            <p>{{ $salutation }}</p>
        @else
            <p>@lang('Regards,')<br>{{ config('app.name') }}</p>
        @endif
    </div>

    <!-- Subcopy (caso exista) -->
    @isset($actionText)
        <div class="footer">
            <p>
                @lang("If you're having trouble clicking the \":actionText\" button, copy and paste the URL below into your web browser:", ['actionText' => $actionText])
            </p>
            <p><a href="{{ $actionUrl }}">{{ $displayableActionUrl }}</a></p>
        </div>
    @endisset
</div>
</body>
</html>