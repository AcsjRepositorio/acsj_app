@extends('layouts.masterlayout') 



@section('content')

<!-- componente navbar -->
<x-navbar />


<x-banner-hero/>

<!-- componente com o banner do prato do dia -->



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

<x-cards.meal-carousel :meals="$meals" /> 
    
    <!-- Componente modal com detalhes do prato -->

<x-footer />

@endsection

