<?php

use Illuminate\Http\Request;
use App\Http\Middleware\AdminAcess;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|---------------------------------------------------------------------------
| Rotas Públicas
|---------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');


// Carrinho (Cart)
Route::resource('cart', CartController::class)->only(['index', 'store', 'destroy']);
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*
|---------------------------------------------------------------------------
| Rotas Protegidas por Autenticação
|---------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Edição do usuário pelo administrador autenticado 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Perfil do usuário autenticado para gerenciar suas encomendas
    Route::get('/minhas-encomendas', [OrderController::class, 'minhasEncomendas'])->name('minhas.encomendas');
   
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    // SROTA PARA EXIBIR A TELA DA TROCA DE SENHA
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'show'])
        ->name('password.change');

    // Processa a atualização da senha
    Route::patch('/password/change', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])
        ->name('password.update');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    /*
    |---------------------------------------------------------------------------
    | Rotas de Administrador (middleware AdminAcess)
    |---------------------------------------------------------------------------
    */
    Route::middleware([AdminAcess::class])->group(function () {

        // Gestão de usuários
        Route::get('/adminpanel/manage_users', [UserController::class, 'index'])
            ->name('adminpanel.manage.users');

        // Gestão de refeições
        Route::get('/adminpanel/manage_meals', [MealController::class, 'index'])
            ->name('adminpanel.manage.meals');
        Route::resource('meals', MealController::class);
        // Atualização de estoque (Meal)
        Route::post('adminpanel/manage_meals/{meal}/stock', [MealController::class, 'updateStock'])
            ->name('meals.stock.update');

        // Gestão de usuários (Resource completo)
        Route::resource('users', UserController::class);

        /*
        |---------------------------------------------------------------------------
        | Pedidos Agrupados (manage_order)
        |---------------------------------------------------------------------------
        */
        // Tela principal de consulta/filtro de pedidos agrupados
        Route::get('/adminpanel/manage_order', [DashboardController::class, 'dashboardView'])
            ->name('adminpanel.manage.order');

        // Busca por Order ID ou Nome do Cliente
        Route::get('/adminpanel/manage_order/search', [DashboardController::class, 'search'])
            ->name('adminpanel.manage.order.search');

        // Atualização via formulário (modo tradicional)
        Route::put('/adminpanel/manage_order/update', [DashboardController::class, 'update'])
            ->name('adminpanel.manage.order.update');

        // Atualização de campo via AJAX
        Route::patch('/adminpanel/manage_order/update-field', [DashboardController::class, 'updateField'])
            ->name('adminpanel.manage.order.updateField');

        /*
        |---------------------------------------------------------------------------
        | Overview Simples (sem agrupamento)
        |---------------------------------------------------------------------------
        */
        // Rota que exibe a lista simples de pedidos
        Route::get('/adminpanel/order_overview', [DashboardController::class, 'index'])
            ->name('adminpanel.order.overview');

        Route::group(['prefix' => 'adminpanel'], function () {
            // manage-orders
            Route::get('/manage-orders', [DashboardController::class, 'dashboardView'])
                ->name('adminpanel.manage.order');

            // Busca
            Route::get('/search-orders', [DashboardController::class, 'search'])
                ->name('adminpanel.search.order');

            // Visão Geral (index)
            Route::get('/orders-overview', [DashboardController::class, 'index'])
                ->name('adminpanel.order.overview');

            // Atualização via form
            Route::post('/orders/update', [DashboardController::class, 'update'])
                ->name('adminpanel.orders.update');

            // Manage Order Overview
            Route::get('/manage_order_overview', [DashboardController::class, 'overview'])
                ->name('adminpanel.manage.order.overview');

            // Busca na Overview
            Route::get('/manage_order_overview/search', [DashboardController::class, 'overviewSearch'])
                ->name('adminpanel.manage.order.overview.search');

            // Filtro na Overview
            Route::get('/manage_order_overview/filter', [DashboardController::class, 'overviewFilter'])
                ->name('adminpanel.manage.order.overview.filter');


                Route::delete('/adminpanel/order/delete', [\App\Http\Controllers\DashboardController::class, 'deleteOrders'])->name('adminpanel.order.delete');

        });
    });
});

/*
|---------------------------------------------------------------------------
| Rotas para Pagamento (IfThenPay, etc.)
|---------------------------------------------------------------------------
*/
Route::post('/payment/process', [PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Callback para MB WAY (se necessário)
Route::post('/payment/mbway/callback', [PaymentController::class, 'mbwayCallback'])
    ->name('payment.mbway.callback');

// Rota para verificar status MB WAY
Route::get('/payment/mbway/status', [PaymentController::class, 'checkMbWayStatus'])
    ->name('payment.mbway.status');

/*
|---------------------------------------------------------------------------
| Outras Rotas
|---------------------------------------------------------------------------
*/
// Atualizar quantidade dos itens do carrinho
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])
    ->name('cart.updateQuantity');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');


//Rota para selecionar bebidas

 Route::post('cart/bulk-store', [CartController::class, 'bulkStore'])->name('cart.bulk.store');


// Lógica para definir o "prato do dia". Se não houver, pode ser null.
Route::get('/', function () {
    $mealOfTheDay =  null;
    return view('home', ['mealOfTheDay' => $mealOfTheDay]);
})->name('home');
/*
|---------------------------------------------------------------------------
| Cookies
|---------------------------------------------------------------------------
*/
Route::post('/accept-cookie', function (Request $request) {
    $minutes = 525600; 
    Cookie::queue('cookie_consent', 'aceito', $minutes);
    return response()->json(['status' => 'ok']);
})->name('accept-cookie');

require __DIR__ . '/auth.php';
