<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\AdminAcess;

// Rotas públicas
Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('cart', CartController::class)->only(['index', 'store', 'destroy']);
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {

    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rotas de administrador
    Route::middleware([AdminAcess::class])->group(function () {

        // Gestão de usuários e refeições
        Route::get('/adminpanel/manage_users', [UserController::class, 'index'])->name('adminpanel.manage.users');
        Route::get('/adminpanel/manage_meals', [MealController::class, 'index'])->name('adminpanel.manage.meals');

        // Tela de consulta e filtro de pedidos (agrupados)
        Route::get('/adminpanel/manage_order', [DashboardController::class, 'dashboardView'])->name('adminpanel.manage.order');

        // Busca server‑side por Order ID ou nome do cliente
        Route::get('/adminpanel/manage_order/search', [DashboardController::class, 'search'])
            ->name('adminpanel.manage.order.search');

        // Atualização dos pedidos
        Route::put('/adminpanel/manage_order/update', [DashboardController::class, 'update'])->name('adminpanel.manage.order.update');

        // Overview simples dos pedidos (sem agrupamento)
        Route::get('/adminpanel/order_overview', [DashboardController::class, 'index'])->name('adminpanel.order.overview');

        // Recursos
        Route::resource('users', UserController::class);
        Route::resource('meals', MealController::class);
    });

    // Rotas com prefixo "adminpanel" (rotas adicionais ou alternativas)
    Route::group(['prefix' => 'adminpanel'], function () {

        // Rota para a tela de gerenciamento com filtros e pesquisa (agrupados)
        Route::get('/manage-orders', [DashboardController::class, 'dashboardView'])
            ->name('adminpanel.manage.order');

        // Rota opcional para pesquisa separada
        Route::get('/search-orders', [DashboardController::class, 'search'])
            ->name('adminpanel.search.order');

        // Rota para overview simples dos pedidos (caso seja necessária)
        Route::get('/orders-overview', [DashboardController::class, 'index'])
            ->name('adminpanel.order.overview');

        // Rota para atualização dos pedidos (via POST)
        Route::post('/orders/update', [DashboardController::class, 'update'])
            ->name('adminpanel.orders.update');

        // Rotas para a página de overview com filtros e busca
        Route::get('/manage_order_overview', [DashboardController::class, 'overview'])
            ->name('adminpanel.manage.order.overview');
        Route::get('/manage_order_overview/search', [DashboardController::class, 'overviewSearch'])
            ->name('adminpanel.manage.order.overview.search');
        Route::get('/manage_order_overview/filter', [DashboardController::class, 'overviewFilter'])
            ->name('adminpanel.manage.order.overview.filter');
    });

   
});

 // Rotas para pagamento (ifthenpay) – não precisam de autenticação
 Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
 Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
 Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
 Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

 // Rota para atualizar quantidades dos itens do carrinho
 Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

 // Checkout
 Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

 require __DIR__ . '/auth.php';
