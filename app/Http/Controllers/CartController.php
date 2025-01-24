<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meal;

class CartController extends Controller
{
    // Exibe o carrinho
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('components.cart', compact('cart'));
    }

    // Adiciona item ao carrinho
    public function store(Request $request)
    {
        $meal = Meal::findOrFail($request->meal_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$meal->id])) {
            $cart[$meal->id]['quantity'] += 1; 
        } else {
            $cart[$meal->id] = [
                'name'        => $meal->name,
                'price'       => $meal->price,
                'quantity'    => 1,
                'photo'       => $meal->photo,
                'day_of_week' => $meal->day_of_week
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Item adicionado ao carrinho!');
    }

    // Remove item do carrinho
    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item removido do carrinho!');
    }

    // Limpa o carrinho inteiro
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Carrinho limpo com sucesso!');
    }

    // NOVO: Atualiza as quantidades do carrinho
    public function updateQuantity(Request $request)
    {
        $mealId = $request->input('meal_id');
        $newQty = (int) $request->input('quantity');
    
        $cart = session()->get('cart', []);
    
        if (isset($cart[$mealId])) {
            // Quantidade mínima = 1
            $cart[$mealId]['quantity'] = max(1, $newQty);
            session()->put('cart', $cart);
        }
    
        // Retorna algo simples em JSON
        return response()->json(['success' => true]);
    }
}
