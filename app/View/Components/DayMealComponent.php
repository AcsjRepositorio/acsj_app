<?php

namespace App\View\Components;

use App\Models\Meal;
use Carbon\Carbon;
use Illuminate\View\Component;

class DayMealComponent extends Component
{
    public $mealOfTheDay;

    public function __construct()
    {
        $today = strtolower(Carbon::now()->locale('pt_PT')->dayName); // Nome do dia em letras minúsculas
        $this->mealOfTheDay = Meal::where('day_of_week', $today)->first();
    }

    public function render()
    {
        return view('components.day-meal-component');
    }
}
