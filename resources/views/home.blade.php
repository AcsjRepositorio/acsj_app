@extends('layouts.masterlayout') {{-- Certifique-se de que este layout está correto --}}

@section('content')
    @auth
        <x-user-dropdown :user="auth()->user()" />
    @else
        <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
        @endif
    @endauth

    <h1>Home Page</h1>

    <div class="meal-cards">
        @foreach ($meals as $meal)
            <x-cards.meal-week :meal="$meal" />
        @endforeach
    </div>
@endsection

