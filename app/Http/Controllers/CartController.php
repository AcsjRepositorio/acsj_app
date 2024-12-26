<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Adicione esta linha
use App\Models\Meal;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [];
    
        if (Auth::check()) {
            // Carrinho para usuários autenticados
            $cartItems = CartItem::where('user_id', Auth::id())->get();
        } else {
            // Carrinho para visitantes (sessão)
            $cartSession = session('cart', []);
    
            foreach ($cartSession as $mealId => $item) {
                $cartItems[] = (object) [
                    'meal' => (object) [
                        'name' => $item['name'],
                        'photo' => $item['photo'],
                        'price' => $item['price'],
                    ],
                    'quantity' => $item['quantity'],
                ];
            }
        }
    
        // Passe a variável para a view correta
        return view('components.sidebar.side-bar-cart', compact('cartItems'));
    }


    public function store(Request $request)
{
    $mealId = $request->input('meal_id');
    $quantity = 1; // Padrão: 1 unidade por vez

    // Verifica se o usuário está autenticado
    $userId = Auth::check() ? Auth::id() : null;
    $sessionId = Auth::check() ? null : session()->getId();

    // Salvar no banco de dados
    $cartItem = CartItem::updateOrCreate(
        [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'meal_id' => $mealId,
        ],
        [
            'quantity' => DB::raw('quantity + 1'), // Incrementar quantidade
        ]
    );

    return response()->json([
        'message' => 'Produto adicionado ao carrinho!',
    ]);
}


public function getCartItems()
{
    $cartItems = Auth::check()
        ? CartItem::with('meal')->where('user_id', Auth::id())->get()
        : CartItem::with('meal')->where('session_id', session()->getId())->get();

    return view('components.sidebar.side-bar-cart', compact('cartItems'));
}

}
