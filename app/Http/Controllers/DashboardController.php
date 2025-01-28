<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboardView()
    {
        // Busca todas as ordens com os pratos relacionados e carrega a relação 'meal'
        $orders = Order::with('meals')->get();

        // Monta um array para agrupar por data (DD/MM/YYYY) e pickup_time
        $groupedData = [];

        foreach ($orders as $order) {
            foreach ($order->meals as $meal) {
                // Obter a data a partir de 'day_week_start' na tabela 'meals'
                $date = Carbon::parse($meal->day_week_start)->format('d/m/Y');

                // Obter pickup_time
                $pickupTime = $meal->pivot->pickup_time ?? 'Não definido';

                // Obter os demais campos
                $note       = $meal->pivot->note ?? '';
                $quantity   = $meal->pivot->quantity ?? 1;

                // Agrupar os dados
                $groupedData[$date][$pickupTime][] = [
                    'order_meal_id'      => $meal->pivot->id, // ID da pivot
                    'order_id'           => $order->order_id,
                    'customer_name'      => $order->customer_name,
                    'customer_email'     => $order->customer_email,
                    'meal_name'          => $meal->name,
                    'quantity'           => $quantity,
                    'note'               => $note,
                    'pickup_time'        => $pickupTime,
                    'disponivel_preparo' => $meal->pivot->disponivel_preparo,
                    'entregue'           => $meal->pivot->entregue,
                ];
            }
        }

        return view('adminpanel.manage_order', compact('groupedData'));
    }

    public function index()
    {
        // Busca todas as ordens com os pratos relacionados
        $orders = Order::with('meals')->get();
        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * Atualiza os campos "Disponível para Preparo" e "Entregue" nos pedidos.
     */
    public function update(Request $request)
    {
        // Valida os dados recebidos
        $request->validate([
            'disponivel_preparo.*' => 'required|in:sim,nao',
            'entregue.*' => 'required|in:sim,nao',
        ]);

        $disponivelPreparo = $request->input('disponivel_preparo', []);
        $entregue = $request->input('entregue', []);

        // Inicia uma transação para garantir a integridade dos dados
        DB::beginTransaction();

        try {
            // Atualizar Disponível para Preparo
            foreach ($disponivelPreparo as $orderMealId => $value) {
                $orderMeal = OrderMeal::find($orderMealId);
                if ($orderMeal) {
                    $orderMeal->disponivel_preparo = $value === 'sim';
                    $orderMeal->save();
                }
            }

            // Atualizar Entregue
            foreach ($entregue as $orderMealId => $value) {
                $orderMeal = OrderMeal::find($orderMealId);
                if ($orderMeal) {
                    $orderMeal->entregue = $value === 'sim';
                    $orderMeal->save();
                }
            }

            DB::commit();

            return redirect()->route('adminpanel.manage.order')->with('success', 'Pedidos atualizados com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('adminpanel.manage.order')->with('error', 'Erro ao atualizar os pedidos: ' . $e->getMessage());
        }
    }
}
