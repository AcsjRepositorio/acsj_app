<?php

use App\Http\Middleware\AdminAcess;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\UserController;

//Para integração com o Ifthenpay
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Rotas públicas
Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('cart', CartController::class)->only(['index', 'store', 'destroy']);
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Rotas de pagamento

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rotas de administrador
    Route::middleware([AdminAcess::class])->group(function () {
       
        Route::get('/adminpanel/manage_users', [UserController::class, 'index'])->name('adminpanel.manage.users');
        Route::get('/adminpanel/manage_meals', [MealController::class, 'index'])->name('adminpanel.manage.meals');

        Route::get('/adminpanel/manage_order', [DashboardController::class, 'dashboardView'])->name('adminpanel.manage.order');

        Route::get('/adminpanel/order_overview', [DashboardController::class, 'index'])->name('adminpanel.order.overview');
        
        Route::resource('users', UserController::class);
        Route::resource('meals', MealController::class);
    });
    
});


//Rotas para pagamento(ifthenpay) Não precisam de autenticação(O usuário pode comprar sem login)
Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Rota para atualizar quantidades de todos os itens do carrinho
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');











require __DIR__ . '/auth.php';

