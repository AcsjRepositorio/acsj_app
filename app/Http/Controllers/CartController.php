<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class CartController extends Controller
{
    // Exibe o carrinho
    public function index()
    {
        // Garante que o valor inicial do carrinho seja sempre um array
        $cart = session()->get('cart', []); 
        return view('cart.index', compact('cart'));
    }

    // Adiciona item ao carrinho
    public function store(Request $request)
    {
        $meal = Meal::findOrFail($request->meal_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$meal->id])) {
            $cart[$meal->id]['quantity'] += 1; // Incrementa a quantidade se já existir
        } else {
            $cart[$meal->id] = [
                'name' => $meal->name,
                'price' => $meal->price,
                'quantity' => 1,
                'photo' => $meal->photo,
                'day_of_week' => $meal->day_of_week
            ];
        }

        session()->put('cart', $cart); // Salva na sessão
        return redirect()->back()->with('success', 'Item adicionado ao carrinho!');
    }

    // Remove item do carrinho
    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]); // Remove o item
            session()->put('cart', $cart); // Atualiza a sessão
        }
        return redirect()->back()->with('success', 'Item removido do carrinho!');
    }

    // Limpa o carrinho inteiro
    public function clear()
    {
        session()->forget('cart'); // Remove o carrinho inteiro
        return redirect()->route('cart.index')->with('success', 'Carrinho limpo com sucesso!');
    }
}
