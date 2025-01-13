<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as LaravelSession;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function checkout(Request $request)
{
    Stripe::setApiKey(config('stripe.sk'));

    $cart = LaravelSession::get('cart', []);

    if (empty($cart)) {
        return back()->with('error', 'Seu carrinho está vazio.');
    }

    $lineItems = [];

    foreach ($cart as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['name'],
                ],
                'unit_amount' => $item['price'] * 100,
            ],
            'quantity' => $item['quantity'],
        ];
    }

    // Obter o método de pagamento selecionado
    $paymentMethod = $request->input('payment_method', 'card');

    try {
        $session = StripeSession::create([
            'payment_method_types' => [$paymentMethod],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cart.index'),
        ]);

        return redirect()->away($session->url);
    } catch (\Exception $e) {
        return back()->with('error', 'Erro ao iniciar o pagamento: ' . $e->getMessage());
    }
}

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Sessão inválida.');
        }

        Stripe::setApiKey(config('stripe.sk'));

        try {
            $session = StripeSession::retrieve($sessionId);

            // Aqui você pode verificar o status do pagamento e salvar informações no banco de dados

            // Limpar o carrinho após o pagamento bem-sucedido
            LaravelSession::forget('cart');

            return view('success', ['session' => $session]);
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erro ao recuperar a sessão de pagamento: ' . $e->getMessage());
        }
    }
}
