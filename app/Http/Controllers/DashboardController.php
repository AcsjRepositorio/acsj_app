<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMeal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * 1) Exibe os pedidos agrupados (manage_order.blade.php).
     *    - Filtros: selectedDate (dd/mm/yyyy), query (nome ou order_id), additional_filter (horario/disponivel/entregue).
     *    - Pagina 10 pedidos por vez.
     */
    public function dashboardView(Request $request)
    {
        $selectedDate     = $request->input('selectedDate');      // Data no formato dd/mm/yyyy
        $queryParam       = $request->input('query');             // Termo de busca
        $additionalFilter = $request->input('additional_filter'); // 'horario', 'disponivel', 'entregue'
        $pickupWindow     = $request->input('pickup_window');     // Ex: "12h15 - 12h30"

        // 1) Monta query de pedidos (somente pagos, se existir scope 'paid')
        $baseQuery = Order::paid()->with('meals');

        // 2) Se tiver busca (nome ou order_id)
        if ($queryParam) {
            $baseQuery->where(function($q) use ($queryParam) {
                $q->where('order_id', 'like', "%{$queryParam}%")
                  ->orWhere('customer_name', 'like', "%{$queryParam}%");
            });
        }

        // 3) Pagina 10 pedidos
        $orders = $baseQuery->paginate(10);

        // 4) Agrupa os pedidos da página atual
        $groupedData = [];
        foreach ($orders as $order) {
            foreach ($order->meals as $meal) {
                // day_week_start => assumindo que está em meals (outra tabela)
                $rawDate = Carbon::parse($meal->day_week_start)->format('d/m/Y');
                $pickupTime = $meal->pivot->pickup_time ?? 'Não definido';
                $note       = $meal->pivot->note ?? '';
                $quantity   = $meal->pivot->quantity ?? 1;

                $groupedData[$rawDate][$pickupTime][] = [
                    'order_meal_id'      => $meal->pivot->id,
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

        // 5) Se houver data selecionada, filtra em memória
        if ($selectedDate) {
            $filteredData = [];
            foreach ($groupedData as $rawDate => $horarios) {
                if ($rawDate === $selectedDate) {
                    $filteredData[$rawDate] = $horarios;
                }
            }
            $groupedData = $filteredData;
        }

        // 6) Se houver additionalFilter e selectedDate, filtra mais em memória
        if ($selectedDate && $additionalFilter) {
            foreach ($groupedData as $date => $horarios) {
                foreach ($horarios as $pickupTime => $items) {
                    $filteredItems = [];
                    foreach ($items as $item) {
                        if ($additionalFilter === 'horario') {
                            if ($pickupWindow && trim($item['pickup_time']) === trim($pickupWindow)) {
                                $filteredItems[] = $item;
                            }
                        } elseif ($additionalFilter === 'disponivel') {
                            if ($item['disponivel_preparo'] === true) {
                                $filteredItems[] = $item;
                            }
                        } elseif ($additionalFilter === 'entregue') {
                            if ($item['entregue'] === true) {
                                $filteredItems[] = $item;
                            }
                        }
                    }
                    if (!empty($filteredItems)) {
                        $groupedData[$date][$pickupTime] = $filteredItems;
                    } else {
                        unset($groupedData[$date][$pickupTime]);
                    }
                }
                if (empty($groupedData[$date])) {
                    unset($groupedData[$date]);
                }
            }
        }

        // 7) Retorna para a view
        return view('adminpanel.manage_order', [
            'groupedData' => $groupedData, // dados agrupados
            'orders'      => $orders,      // objeto paginado ({{ $orders->links() }})
        ]);
    }

    /**
     * 2) Método de busca específico (query) para manage_order.
     *    Filtra (order_id ou customer_name), pagina e agrupa.
     */
    public function search(Request $request)
    {
        $queryParam = $request->input('query', '');
        if (!$queryParam) {
            return redirect()->route('adminpanel.manage.order');
        }

        // 1) Busca + pagina
        $orders = Order::paid()
            ->with('meals')
            ->where(function($q) use ($queryParam) {
                $q->where('order_id', 'like', "%{$queryParam}%")
                  ->orWhere('customer_name', 'like', "%{$queryParam}%");
            })
            ->paginate(10);

        // 2) Agrupa resultados da página
        $groupedData = [];
        foreach ($orders as $order) {
            foreach ($order->meals as $meal) {
                $rawDate = Carbon::parse($meal->day_week_start)->format('d/m/Y');
                $pickupTime = $meal->pivot->pickup_time ?? 'Não definido';

                $groupedData[$rawDate][$pickupTime][] = [
                    'order_meal_id'      => $meal->pivot->id,
                    'order_id'           => $order->order_id,
                    'customer_name'      => $order->customer_name,
                    'customer_email'     => $order->customer_email,
                    'meal_name'          => $meal->name,
                    'quantity'           => $meal->pivot->quantity ?? 1,
                    'note'               => $meal->pivot->note ?? '',
                    'pickup_time'        => $pickupTime,
                    'disponivel_preparo' => $meal->pivot->disponivel_preparo,
                    'entregue'           => $meal->pivot->entregue,
                ];
            }
        }

        return view('adminpanel.manage_order', [
            'groupedData' => $groupedData,
            'orders'      => $orders,
            'search_query'=> $queryParam
        ]);
    }

    /**
     * 3) Atualiza pedidos via form (modo tradicional).
     *    (Caso você ainda use esse método.)
     */
    public function update(Request $request)
    {
        // Valida se todos os selects recebidos são 'sim' ou 'nao'
        $request->validate([
            'disponivel_preparo.*' => 'required|in:sim,nao',
            'entregue.*'           => 'required|in:sim,nao',
        ]);

        $disponivelPreparo = $request->input('disponivel_preparo', []);
        $entregue          = $request->input('entregue', []);

        $allPivotIds = array_unique(array_merge(
            array_keys($disponivelPreparo),
            array_keys($entregue)
        ));

        DB::beginTransaction();
        try {
            foreach ($allPivotIds as $pivotId) {
                $prepVal = $disponivelPreparo[$pivotId] ?? 'nao';
                $entVal  = $entregue[$pivotId]          ?? 'nao';

                $orderMeal = OrderMeal::find($pivotId);
                if ($orderMeal) {
                    $orderMeal->disponivel_preparo = ($prepVal === 'sim');
                    $orderMeal->entregue           = ($entVal === 'sim');
                    $orderMeal->save();
                }
            }

            DB::commit();
            return redirect()
                ->route('adminpanel.manage.order')
                ->with('success', 'Pedidos atualizados com sucesso.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('adminpanel.manage.order')
                ->with('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }

    /**
     * 4) Atualiza UM campo via AJAX (disponivel_preparo ou entregue).
     */
    public function updateField(Request $request)
    {
        $request->validate([
            'pivot_id' => 'required|integer',
            'field'    => 'required|in:entregue,disponivel_preparo',
            'value'    => 'required|in:sim,nao',
        ]);

        $pivot = OrderMeal::find($request->pivot_id);
        if (!$pivot) {
            return response()->json(['error' => 'Pivot não encontrado'], 404);
        }

        $boolValue = ($request->value === 'sim');
        if ($request->field === 'entregue') {
            $pivot->entregue = $boolValue;
        } else {
            $pivot->disponivel_preparo = $boolValue;
        }
        $pivot->save();

        return response()->json(['success' => true]);
    }

    /**
     * 5) VISÃO GERAL: Exibe pedidos numa lista simples (order_overview).
     *    Pagina 10, sem agrupamento.
     */
    public function overview(Request $request)
    {
        $orders = Order::with('meals')->paginate(10);

        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * 6) Busca na visão geral (por order_id ou customer_name).
     */
    public function overviewSearch(Request $request)
    {
        $query = $request->input('query', '');

        $orders = Order::with('meals')
            ->where(function ($q2) use ($query) {
                $q2->where('order_id', 'like', "%{$query}%")
                   ->orWhere('customer_name', 'like', "%{$query}%");
            })
            ->paginate(10);

        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * 7) Filtro (data, status, método) na visão geral.
     */
    public function overviewFilter(Request $request)
    {
        $selectedDate  = $request->input('selectedDate', '');
        $paymentStatus = $request->input('payment_status', '');
        $paymentMethod = $request->input('payment_method', '');

        $ordersQuery = Order::with('meals');

        if (!empty($selectedDate)) {
            // Filtra pela data do pedido (created_at)
            $ordersQuery->whereDate('created_at', $selectedDate);
        }
        if (!empty($paymentStatus)) {
            $ordersQuery->where('payment_status', $paymentStatus);
        }
        if (!empty($paymentMethod)) {
            $ordersQuery->where('payment_method', $paymentMethod);
        }

        $orders = $ordersQuery->paginate(10);

        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * 8) Exemplo de index (se precisar).
     */
    public function index()
    {
        // Poderia ser outra listagem simples
        $orders = Order::with('meals')->paginate(10);
        return view('adminpanel.order_overview', compact('orders'));
    }
}
