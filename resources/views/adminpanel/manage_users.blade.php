@extends('adminlte::page')

@section('title', 'Dashboard - Usuários')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h1 class="h2">Gerenciar Usuários</h1>
        <a href="{{route('users.create')}}" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i>Novo Usuário
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Foto</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo de usuário</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="text-center">
                        @if($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto de {{ $user->name }}" class="rounded-circle" style="width: 50px; height: 50px;">
                        @else
                            
                        @endif
                    </td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->user_type === 1)
                            Administrador
                        @elseif($user->user_type === 2)
                            Usuário Padrão
                        @else
                            Não definido
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
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
