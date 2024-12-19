@extends('layouts.masterlayout')
@section('content')


@auth
    <x-user-dropdown :user="auth()->user()" />
@else
    <a href="{{ route('login') }}" class="btn btn-primary">Log in</a>
    @if (Route::has('register'))
        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
    @endif
@endauth



<h1>Home page</h1>

<x-cads.meal-week/>

