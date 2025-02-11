@extends('adminlte::page')

@section('title', 'Dashboard - Descontos')

@section('content')
    <h1>Gerenciar Desconto</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('adminpanel.discount.store') }}" method="POST" style="max-width: 300px;" class="mt-4">
        @csrf
        <div class="input-group mb-3">
            <span class="input-group-text">%</span>
            <input type="text" name="discount_percentage" class="form-control"
                   placeholder="0.00"
                   value="{{ old('discount_percentage', $discount->discount_percentage ?? '') }}">
        </div>
        @error('discount_percentage')
            <div class="text-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary">Salvar Desconto</button>
    </form>
@endsection
