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
        $additionalFilter  = $request->input('additional_filter');   // 'horario', 'disponivel' ou 'entregue'
        $pickupWindow      = $request->input('pickup_window');       // Caso additional_filter == 'horario'

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
                            // Compara de forma exata (aplicando trim para evitar espaços extras)
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
     * Exibe uma overview simples dos pedidos.
     */
    public function index()
    {
        $orders = Order::with('meals')->get();
        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * Atualiza os campos "Disponível para Preparo" e "Entregue" dos pedidos.
     *
     * Alteramos aqui o update para combinar as atualizações em um único loop, garantindo que
     * o campo 'entregue' seja atualizado corretamente.
     */
    public function update(Request $request)
    {
        // Valida os valores recebidos do formulário
        $request->validate([
            'disponivel_preparo.*' => 'required|in:sim,nao',
            'entregue.*'           => 'required|in:sim,nao',
        ]);

        $disponivelPreparo = $request->input('disponivel_preparo', []);
        $entregue = $request->input('entregue', []);

        // Obtém todos os IDs enviados (pode ser que alguma linha exista em apenas um dos arrays)
        $orderMealIds = array_unique(array_merge(array_keys($disponivelPreparo), array_keys($entregue)));

        DB::beginTransaction();
        try {
            foreach ($orderMealIds as $orderMealId) {
                $dpValue = isset($disponivelPreparo[$orderMealId]) ? $disponivelPreparo[$orderMealId] : 'nao';
                $entValue = isset($entregue[$orderMealId]) ? $entregue[$orderMealId] : 'nao';

                OrderMeal::where('id', $orderMealId)->update([
                    'disponivel_preparo' => ($dpValue === 'sim'),
                    'entregue'           => ($entValue === 'sim'),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pedidos atualizados com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar pedidos: ' . $e->getMessage());
        }
    }

    //////////////////////////
    // Métodos para Overview
    //////////////////////////

    /**
     * Exibe a página de overview dos pedidos sem filtros.
     */
    public function overview(Request $request)
    {
        $orders = Order::with('meals')->get();
        return view('adminpanel.order_overview', compact('orders'));
    }

    /**
     * Realiza a busca por pedido ou nome do cliente na visão de overview.
     */
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

    /**
     * Aplica os filtros na visão de overview.
     * Filtra por data do pedido, status do pagamento e método de pagamento.
     */
    public function overviewFilter(Request $request)
    {
        $selectedDate   = $request->input('selectedDate', '');
        $paymentStatus  = $request->input('payment_status', '');
        $paymentMethod  = $request->input('payment_method', '');

        $ordersQuery = Order::with('meals');

        // Filtra por data do pedido (campo created_at; formato esperado: yyyy-mm-dd)
        if (!empty($selectedDate)) {
            $ordersQuery->whereDate('created_at', $selectedDate);
        }

        // Filtra por status do pagamento (ex.: pending ou paid)
        if (!empty($paymentStatus)) {
            $ordersQuery->where('payment_status', $paymentStatus);
        }

        // Filtra por método de pagamento (ex.: card, mbway, multibanco)
        if (!empty($paymentMethod)) {
            $ordersQuery->where('payment_method', $paymentMethod);
        }

        $orders = $ordersQuery->get();

        return view('adminpanel.order_overview', compact('orders'));
    }
}
