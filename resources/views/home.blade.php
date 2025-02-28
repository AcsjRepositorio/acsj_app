@extends('layouts.masterlayout') 



@section('content')

<!-- componente navbar -->
<x-navbar/>


<x-banner-hero/>





<!-- componente Carrossel com cards dos pratos -->

    
<x-modal.meal-details />


    <!-- Componente do carrinho -->
    <x-cart/>



    
 
    <!-- @php
    // Chama o método estático e obtém a collection agrupada
    $mealsByDay = \App\Models\Meal::getAllMealsByDay();
@endphp -->

<!-- Agora passa essa variável para o seu componente -->



<!-- Componente "ficheiro" com os dias da semana -->
<div id="scroll-day-meal">
<x-meal-tabs :mealsByDay="$mealsByDay" />
</div>


<x-beaveragesbanner/>

<x-cards.meal-carousel :meals="$meals" /> 
    


<x-footer />

@endsection

