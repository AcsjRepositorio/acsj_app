<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Exibe os pedidos agrupados por data e pickup_time com base nos filtros:
     * - Data
     * - Filtro adicional (horario, disponivel ou entregue)
     * - Janela de horário (para filtro adicional "horario")
     * - Termo de busca (Order ID ou Nome) se informado
     */
    public function dashboardView(Request $request)
    {
        // Captura os parâmetros do formulário de filtro
        $selectedDate      = $request->input('selectedDate');      // Data no formato dd/mm/yyyy
        $queryParam        = $request->input('query');             // Termo de busca (Order ID ou Nome)
        $additionalFilter  = $request->input('additional_filter'); // 'horario', 'disponivel' ou 'entregue'
        $pickupWindow      = $request->input('pickup_window');     // Caso additional_filter == 'horario'

        // Se houver termo de busca, filtra a consulta no banco; caso contrário, pega todos os pedidos
        if ($queryParam) {
            $orders = Order::with('meals')
                ->where(function($q) use ($queryParam) {
                    // Filtra por order_id ou nome do cliente (usando LIKE)
                    $q->where('order_id', 'like', "%{$queryParam}%")
                      ->orWhere('customer_name', 'like', "%{$queryParam}%");
                })
                ->get();
        } else {
            $orders = Order::with('meals')->get();
        }

        // Monta o array agrupado por data (dd/mm/yyyy) e pickup_time
        $groupedData = [];
        foreach ($orders as $order) {
            foreach ($order->meals as $meal) {
                // Formata a data para dd/mm/yyyy
                // (Caso você não queira mais usar day_week_start,
                //  basta trocar para algo como pivot->day_of_week ou remover o Carbon.)
                $date = Carbon::parse($meal->day_week_start)->format('d/m/Y');

                // Define o pickup time (ou "Não definido")
                $pickupTime = $meal->pivot->pickup_time ?? 'Não definido';
                $note       = $meal->pivot->note ?? '';
                $quantity   = $meal->pivot->quantity ?? 1;

                $groupedData[$date][$pickupTime][] = [
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

        // Se houver data selecionada, filtra os grupos pela data
        if ($selectedDate) {
            $filteredData = [];
            foreach ($groupedData as $rawDate => $horarios) {
                if ($rawDate === $selectedDate) {
                    $filteredData[$rawDate] = $horarios;
                }
            }
            $groupedData = $filteredData;
        }

        // Se houver filtro adicional E data selecionada, filtra os itens dentro de cada grupo
        if ($selectedDate && $additionalFilter) {
            foreach ($groupedData as $date => $horarios) {
                foreach ($horarios as $pickupTime => $items) {
                    $filteredItems = [];
                    foreach ($items as $item) {
                        if ($additionalFilter == 'horario') {
                            if ($pickupWindow && trim($item['pickup_time']) === trim($pickupWindow)) {
                                $filteredItems[] = $item;
                            }
                        } elseif ($additionalFilter == 'disponivel') {
                            if ($item['disponivel_preparo'] === true) {
                                $filteredItems[] = $item;
                            }
                        } elseif ($additionalFilter == 'entregue') {
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

        return view('adminpanel.manage_order', compact('groupedData'));
    }

    /**
     * Método search para busca server-side usando o campo de pesquisa independente.
     * Este método filtra os pedidos com base no termo (Order ID ou Nome do Cliente)
     * e agrupa os resultados da mesma forma que dashboardView().
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Se não for informado nenhum termo, redireciona para a tela principal de gestão
        if (!$query) {
            return redirect()->route('adminpanel.manage.order');
        }

        // Busca os pedidos cujo order_id ou customer_name contenha o termo
        $orders = Order::with('meals')
            ->where('order_id', 'like', "%{$query}%")
            ->orWhere('customer_name', 'like', "%{$query}%")
            ->get();

        // Agrupa os resultados por data (dd/mm/yyyy) e pickup_time
        $groupedData = [];
        foreach ($orders as $order) {
            foreach ($order->meals as $meal) {
                $date = Carbon::parse($meal->day_week_start)->format('d/m/Y');
                $pickupTime = $meal->pivot->pickup_time ?? 'Não definido';
                $note = $meal->pivot->note ?? '';
                $quantity = $meal->pivot->quantity ?? 1;

                $groupedData[$date][$pickupTime][] = [
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

        // Retorna a view com os dados agrupados e passa o termo pesquisado para referência (opcional)
        return view('adminpanel.manage_order', compact('groupedData'))
            ->with('search_query', $query);
    }

    /**
     * Exibe uma overview simples dos pedidos (se quiser).
     */
    public function index()
    {
        $orders = Order::with('meals')->get();
        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * Atualiza os campos "Disponível para Preparo" e "Entregue" dos pedidos (com form tradicional).
     * (Mantido caso você use ainda um form e um botão "Salvar alterações".)
     */
    public function update(Request $request)
    {
        // Valida se todos os selects recebidos são 'sim' ou 'nao'
        $request->validate([
            'disponivel_preparo.*' => 'required|in:sim,nao',
            'entregue.*'           => 'required|in:sim,nao',
        ]);

        // Arrays do form: ex.: ['123' => 'sim', '456' => 'nao']
        $disponivelPreparo = $request->input('disponivel_preparo', []);
        $entregue          = $request->input('entregue', []);

        // JUNTA todas as chaves (IDs) para atualizar
        $allPivotIds = array_unique(array_merge(
            array_keys($disponivelPreparo),
            array_keys($entregue)
        ));

        DB::beginTransaction();
        try {
            // Percorre cada ID da pivot
            foreach ($allPivotIds as $pivotId) {
                // Se não estiver setado em algum array, default = 'nao'
                $prepVal = $disponivelPreparo[$pivotId] ?? 'nao';
                $entVal  = $entregue[$pivotId]          ?? 'nao';

                // Localiza o registro do pivot
                $orderMeal = OrderMeal::find($pivotId);
                if ($orderMeal) {
                    // Se no BD for boolean/tinyint, convertemos 'sim'->true / 'nao'->false
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
                ->with('error', 'Erro ao atualizar os pedidos: ' . $e->getMessage());
        }
    }

    //////////////////////////
    // Métodos para Overview
    //////////////////////////

    public function overview(Request $request)
    {
        $orders = Order::with('meals')->get();
        return view('adminpanel.order_overview', compact('orders'));
    }

    public function overviewSearch(Request $request)
    {
        $query = $request->input('query', '');

        $orders = Order::with('meals')
            ->where(function ($q) use ($query) {
                $q->where('order_id', 'like', "%{$query}%")
                  ->orWhere('customer_name', 'like', "%{$query}%");
            })
            ->get();

        return view('adminpanel.order_overview', compact('orders'));
    }

    public function overviewFilter(Request $request)
    {
        $selectedDate   = $request->input('selectedDate', '');
        $paymentStatus  = $request->input('payment_status', '');
        $paymentMethod  = $request->input('payment_method', '');

        $ordersQuery = Order::with('meals');

        if (!empty($selectedDate)) {
            $ordersQuery->whereDate('created_at', $selectedDate);
        }
        if (!empty($paymentStatus)) {
            $ordersQuery->where('payment_status', $paymentStatus);
        }
        if (!empty($paymentMethod)) {
            $ordersQuery->where('payment_method', $paymentMethod);
        }

        $orders = $ordersQuery->get();
        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * NOVO MÉTODO: Atualiza UM campo via AJAX quando o usuário muda o <select>.
     * Recebe: pivot_id, field (entregue/disponivel_preparo), value (sim/nao).
     */
    public function updateField(Request $request)
    {
        // Validação dos parâmetros do AJAX
        $request->validate([
            'pivot_id' => 'required|integer',
            'field'    => 'required|in:entregue,disponivel_preparo',
            'value'    => 'required|in:sim,nao',
        ]);

        $pivot = OrderMeal::find($request->pivot_id);
        if (!$pivot) {
            return response()->json(['error' => 'Pivot não encontrado'], 404);
        }

        // Se colunas forem boolean, convertemos 'sim'->true e 'nao'->false
        $boolValue = ($request->value === 'sim');

        if ($request->field === 'entregue') {
            $pivot->entregue = $boolValue;
        } else {
            $pivot->disponivel_preparo = $boolValue;
        }
        $pivot->save();

        return response()->json(['success' => true]);
    }
}
