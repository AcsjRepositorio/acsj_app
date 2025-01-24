@extends('adminlte::page')

@section('title', 'Consultar Pedidos')

@section('content_header')
<p>Dashboard - Consulta de Pedidos</p>
@stop

@section('content')


<body>
 

</body>

@stop

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stop


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const accordions = document.querySelectorAll('.accordion .accordion-collapse');
        accordions.forEach(accordion => {
            accordion.classList.remove('show'); // Fechar todos os acordeões inicialmente
        });
    });
</script>