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
        // Valida o ID da refeição
        $request->validate([
            'meal_id' => 'required|integer|exists:meals,id'
        ]);

        $mealId = $request->meal_id;
        $meal = Meal::findOrFail($mealId);

        // Recupera o carrinho atual da sessão ou cria um array vazio
        $cart = session()->get('cart', []);

        // Se o item já estiver no carrinho, incrementa a quantidade (se não ultrapassar o estoque)
        if (isset($cart[$mealId])) {
            // Verifica se a quantidade atual é menor que o estoque disponível
            if ($cart[$mealId]['quantity'] < $meal->stock) {
                $cart[$mealId]['quantity']++;
            } else {
                // Se a quantidade já é igual ao estoque, retorna com uma mensagem de erro
                return redirect()->back()->with('error', 'Quantidade máxima em estoque já adicionada.');
            }
        } else {
            // Se o item ainda não estiver no carrinho, adiciona com quantidade 1 e registra o estoque
            $cart[$mealId] = [
                'name'     => $meal->name,
                'price'    => $meal->price,
                'photo'    => $meal->photo,
                'quantity' => 1,
                'stock'    => $meal->stock, // Armazena o estoque disponível
            ];
        }

        // Salva o carrinho atualizado na sessão
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item adicionado ao carrinho.');
    }

    // Você deve ter também um método para atualizar a quantidade, por exemplo:
    public function updateQuantity(Request $request)
    {
        $data = $request->validate([
            'meal_id'  => 'required|integer|exists:meals,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('cart', []);
        $mealId = $data['meal_id'];
        $quantity = $data['quantity'];

        //  estoque atual do produto 
        if (isset($cart[$mealId])) {
            // Se a quantidade enviada ultrapassar o estoque armazenado, corrige para o valor máximo
            $maxStock = $cart[$mealId]['stock'] ?? 0;
            $cart[$mealId]['quantity'] = ($quantity > $maxStock) ? $maxStock : $quantity;
        }

        session()->put('cart', $cart);

        return response()->json(['success' => true]);
    }

    // Remove item do carrinho
    public function destroy($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Item removido do carrinho.');
    }

    // Limpa todo o carrinho
    public function clear()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'Carrinho limpo.');
    }


    public function bulkStore(Request $request)
    {
        // Decodifica os IDs dos produtos selecionados (espera receber um JSON)
        $selectedIds = json_decode($request->input('selected_ids'), true);
        
        // Recupera o carrinho atual da sessão ou cria um array vazio
        $cart = session()->get('cart', []);
        
        // Para cada ID selecionado, adiciona o produto ao carrinho com quantidade 1
        foreach ($selectedIds as $id) {
            // Busca o produto
            $meal = Meal::find($id);
            if ($meal) {
                // Se o item já existir no carrinho, incrementa a quantidade se houver estoque disponível
                if (isset($cart[$id])) {
                    if ($cart[$id]['quantity'] < $meal->stock) {
                        $cart[$id]['quantity']++;
                    }
                    // Se já atingiu o estoque, podemos ignorar ou enviar mensagem de aviso (opcional)
                } else {
                    // Adiciona o item ao carrinho com quantidade 1
                    $cart[$id] = [
                        'name'     => $meal->name,
                        'price'    => $meal->price,
                        'photo'    => $meal->photo,
                        'quantity' => 1,
                        'stock'    => $meal->stock, // Armazena o estoque disponível
                    ];
                }
            }
        }
        
        // Atualiza o carrinho na sessão
        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', 'Produtos adicionados ao carrinho!');
    }
}

