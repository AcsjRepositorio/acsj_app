@extends('layouts.masterlayout') 



@section('content')

<x-navbar />

<x-day-meal-component />

    <h1>Home Page</h1>
    <div class="meal-cards">
        @foreach ($meals as $meal)
            <x-cards.meal-week :meal="$meal" />
        @endforeach
    </div>

    <!-- Modal include -->
     
    <x-modal.meal-details />
    <x-cart/>


    

@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" ></script >
