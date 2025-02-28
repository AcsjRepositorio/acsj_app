<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Exibe as encomendas do usuário autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function minhasEncomendas()
    {
        // Recupera o usuário autenticado
        $user = Auth::user();

        // Recupera as encomendas do usuário com os relacionamentos necessários
        // Aqui usamos eager loading para evitar N+1
        $orders = $user->orders()->with(['meals' => function ($query) {
            // Se necessário, você pode customizar a consulta dos meals
            $query->withPivot([
                'id', 
                'quantity', 
                'day_of_week', 
                'pickup_time', 
                'note', 
                'disponivel_preparo', 
                'entregue'
            ]);
        }])->orderBy('created_at', 'desc')->get();

        // Retorna a view com os dados
        return view('minhas_encomendas', compact('orders'));
    }

    /**
     * Cria um novo pedido.
     *
     * Permite criar pedidos mesmo para clientes não autenticados,
     * atribuindo null para user_id quando necessário.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validação dos dados recebidos
        $validated = $request->validate([
            'order_id'       => 'required|string',
            'amount'         => 'required|numeric',
            'payment_method' => 'required|string',
            'customer_name'  => 'required|string',
            'customer_email' => 'required|email',
            'nif'            => 'nullable|string',
            // Outros campos se necessários...
        ]);

        // Cria o pedido e atribui os dados
        $order = new Order();
        $order->order_id = $validated['order_id'];
        $order->amount = $validated['amount'];
        $order->payment_method = $validated['payment_method'];
        $order->status = 'pending';
        $order->payment_status = 'pending';
        $order->customer_name = $validated['customer_name'];
        $order->customer_email = $validated['customer_email'];
        $order->nif = $validated['nif'] ?? null;

        // Atribui o user_id se o usuário estiver autenticado; caso contrário, null
        $order->user_id = Auth::check() ? Auth::id() : null;

        $order->save();

        return redirect()->route('minhas.encomendas')
                         ->with('success', 'Pedido realizado com sucesso!');
    }

    /**
     * Exclui um pedido.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        // Verifica se o usuário autenticado pode excluir este pedido
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Você não tem permissão para excluir este pedido.');
        }

        $order->delete();

        return redirect()->route('minhas.encomendas')->with('success', 'Pedido excluído com sucesso!');
    }
}

