<?php


namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\MealController;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


     // Para rendezirar um componente de forma mais simples sem necessariamente 
     // criar uma rota exclusiva para o componente(a função getAllMeals não gera blade)
     // o "*" indica que ele pode ser invocado em qlq página 
     
    public function boot(): void
    {

        Carbon::setLocale('pt_PT');
        view()->composer('*', function ($view){
    $view->with('meals', MealController::getAllMeals());

        });
    }
}
