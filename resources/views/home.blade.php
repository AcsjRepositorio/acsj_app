@extends('layouts.masterlayout') 



@section('content')

<!-- componente navbar -->
<x-navbar />


<!-- componente com o banner do prato do dia -->
<x-day-meal-component />

    <!-- componente Carrossel com cards dos pratos -->

 <!-- <x-cards.meal-carousel :meals="$meals" /> -->
    
<!-- Componente modal com detalhes do prato -->
    
<x-modal.meal-details />


    <!-- Componente do carrinho -->
    <x-cart/>


 
    <!-- @php
    // Chama o método estático e obtém a collection agrupada
    $mealsByDay = \App\Models\Meal::getAllMealsByDay();
@endphp -->

<!-- Agora passa essa variável para o seu componente -->



<!-- Componente "ficheiro" com os dias da semana -->
<x-meal-tabs :mealsByDay="$mealsByDay" />



@endsection
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" ></script >
