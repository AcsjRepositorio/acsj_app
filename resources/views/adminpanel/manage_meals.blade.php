@extends('adminlte::page')

@section('title', 'Dashboard - Refeições da semana')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h2">Gerenciar Refeições</h1>
        <a href="" class="btn btn-success">
            <i class="fa-solid fa-bowl-food me-2"></i> Inserir Refeição
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-light text-center">
            <tr>
                <th style="width: 10%;">Foto</th>
                <th style="width: 20%;">Nome</th>
                <th style="width: 35%;">Descrição</th>
                <th style="width: 10%;">Preço</th>
                <th style="width: 15%;">Categoria</th>
                <th style="width: 10%;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meals as $meal)
                <tr>
                    <!-- Foto -->
                    <td class="text-center align-middle">
                        @if($meal->photo)
                            <img src="{{ asset('storage/' . $meal->photo) }}" alt="Foto de {{ $meal->name }}" class="rounded-circle" style="width: 50px; height: 50px;">
                        @else
                            <span class="text-muted">Sem Foto</span>
                        @endif
                    </td>

                    <!-- Nome -->
                    <td class="align-middle text-center">{{ $meal->name }}</td>

                    <!-- Descrição -->
                    <td class="align-middle" style="max-width: 300px;">{{ $meal->description }}</td>

                    <!-- Preço -->
                    <td class="align-middle text-center">{{ number_format($meal->price, 2, ',', '.') }}</td>

                    <!-- Categoria -->
                    <td class="align-middle text-center">{{ $meal->category->meal_category }}</td>

                    <!-- Ações -->
                    <td class="text-center align-middle">
                        <a href="" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="{{route('meals.destroy',$meal->id)}}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta refeição?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
@stop
