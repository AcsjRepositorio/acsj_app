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
            $query->withPivot(['id', 'quantity', 'day_of_week', 'pickup_time', 'note', 'disponivel_preparo', 'entregue']);
        }])->orderBy('created_at', 'desc')->get();

        // Retorna a view com os dados
        return view('minhas_encomendas', compact('orders'));
    }
}
