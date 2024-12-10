<?php

use App\Http\Middleware\AdminAcess;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('home');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Esta rota foi criada para permitir o middleware AdminAcess
        Route::middleware([AdminAcess::class])->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'dashboardView'])->name('dashboard');
            Route::get('/adminpanel/manage_users', [UserController::class, 'index'])->name('adminpanel.manage.users');

            Route::get('/adminpanel/manage_meals', [MealController::class, 'index'])->name('adminpanel.manage.meals');
            

            Route::resource('users',UserController::class);
            
            Route::resource('meals',MealController::class);

        });
    
    
});



//Rotas públicas sem autenticação 
Route::get('/viewallmeals',[MealController::class,'viewallmeals'])->name('viewallmeals');








require __DIR__.'/auth.php';
