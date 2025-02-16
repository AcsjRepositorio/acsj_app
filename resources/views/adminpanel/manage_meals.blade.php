@extends('adminlte::page')

@section('title', 'Dashboard - Refeições da semana')



@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h2">Gerenciar Refeições</h1>
        <a href="{{route('meals.create')}}" class="btn btn-success">
            <span class="gap-3">Inserir refeição </span>
            <i class="fa-solid fa-utensils ms-2"></i>
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
                <th scope="col" style="width: 5%"></th>
                <th style="width: 5%;">Foto</th>
                <th style="width: 5%;">Nome</th>
                <th style="width: 35%;">Descrição</th>
                <th style="width: 10%;">Preço</th>
                <th style="width: 5%;">Estoque</th>
                <th style="width: 10%;">Categoria</th>
                <th style="width: 10%;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meals as $meal)

            <tr>
                <td class="bg-black text-white justify-content-center p-1" style="height: 50px; margin-right: 5px;">
                    <div class="text-center m-0 d-flex justify-content-center align-items-center" style="writing-mode: vertical-rl; font-size: 12px;">
                    {{ \Carbon\Carbon::parse($meal->day_week_start)->format('d-m-Y') }} - {{ ucfirst($meal->day_of_week) }}

                    </div>
                </td>





                <!-- Foto -->
                <td class="text-center align-middle">
                    <img
                        src="{{ $meal->photo && file_exists(public_path('storage/' . $meal->photo)) 
            ? asset('storage/' . $meal->photo) 
            : asset('images/default-meal.jpg') }}"
                        alt="Foto de {{ $meal->name }}"
                        class="rounded-circle"
                        style="width: 50px; height: 50px;">


                </td>

                <!-- Nome -->
                <td class="align-middle text-center">{{ $meal->name }}</td>

                <!-- Descrição -->
                <td class="align-middle" style="max-width: 300px;">{{ $meal->description }}</td>

                <!-- Preço -->
                <td class="align-middle text-center">€ {{ number_format($meal->price, 2, ',', '.') }}</td>


                <!-- Stock -->

                <td class="align-middle text-center">{{ $meal->stock }}</td>


                <!-- Categoria -->
                <td>{{ $meal->category->meal_category ?? 'Categoria não definida' }}</td>

                <!-- Ações -->
                <td class="text-center align-middle">
                    <a href="{{route('meals.edit', $meal->id)}}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{route('meals.destroy', $meal->id)}}" method="POST" class="d-inline"
                        onsubmit="return confirm('Tem certeza que deseja excluir esta refeição?')">
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

    <!-- Navegação de Paginação -->

</div>
@stop

@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="/css/admin_custom.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">





@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
@stop