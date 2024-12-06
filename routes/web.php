<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\AdminAcess;
use Illuminate\Support\Facades\Route;
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
    });
    
    
});








require __DIR__.'/auth.php';
