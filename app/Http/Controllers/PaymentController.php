<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    /**
     * Processa o pagamento de acordo com o método selecionado.
     */
    public function process(Request $request)
    {
        // Validação dos campos obrigatórios
        $rules = [
            'items'               => 'required|array',
            'items.*.pickup_time' => 'required|string',
            'payment_method'      => 'required|string',
        ];
        $messages = [
            'items.*.pickup_time.required' => 'Por favor selecione o horário de pickup para cada refeição.',
            'payment_method.required'      => 'Por favor selecione um método de pagamento.',
            'payment_method.string'        => 'Método de pagamento inválido.',
        ];

        if (!Auth::check()) {
            $rules['customer_name']  = 'required|string';
            $rules['customer_email'] = 'required|email';
            
            $messages['customer_name.required']  = 'Por favor insira seu nome.';
            $messages['customer_email.required'] = 'Por favor insira seu e-mail.';
            $messages['customer_email.email']    = 'Por favor insira um e-mail válido.';
        }
        $request->validate($rules, $messages);

        $paymentMethod = $request->input('payment_method');
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Carrinho está vazio!');
        }

        // Utiliza o valor final enviado pelo frontend (com a taxa, se aplicável)
        $amount = $request->input('final_total');

        // Gera um identificador único para o pedido
        $ifthenOrderId = 'ORD' . rand(1000, 9999);

        // Cria o pedido com status "pending"
        $order = new Order();
        $order->order_id       = $ifthenOrderId;
        $order->amount         = $amount;
        $order->payment_method = $paymentMethod;
        $order->status         = 'pending';
        $order->payment_status = 'pending';

        if (Auth::check()) {
            $order->user_id        = Auth::id();
            $order->customer_name  = Auth::user()->name;
            $order->customer_email = Auth::user()->email;
        } else {
            $order->customer_name  = $request->input('customer_name');
            $order->customer_email = $request->input('customer_email');
        }

        if ($request->input('add_order_info') === 'yes') {
            $order->order_description = $request->input('order_description');
        }
        $order->save();

        // Associa os itens do carrinho ao pedido
        foreach ($cart as $mealId => $item) {
            $quantity  = $item['quantity'];
            $dayOfWeek = $item['day_of_week'] ?? null;
            $note       = $request->input("items.$mealId.note");
            $pickupTime = $request->input("items.$mealId.pickup_time");

            $order->meals()->attach($mealId, [
                'quantity'    => $quantity,
                'day_of_week' => $dayOfWeek,
                'pickup_time' => $pickupTime,
                'note'        => $note,
            ]);
        }

        // Chama o método de processamento conforme o método de pagamento selecionado
        try {
            switch ($paymentMethod) {
                case 'multibanco':
                    return $this->processMultibanco($ifthenOrderId, $amount, $request);

                case 'mbway':
                    $mbwayPhone = $request->input('mbway_phone');
                    return $this->processMbWay($ifthenOrderId, $amount, $mbwayPhone, $request);

                case 'card':
                    return $this->processCreditCard($ifthenOrderId, $amount, $request);

                default:
                    return redirect()->back()->with('error', 'Método de pagamento não suportado.');
            }
        } catch (\Exception $e) {
            \Log::error("Erro no pagamento: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o pagamento.');
        }
    }

    /**
     * Processa o pagamento via Multibanco.
     */
    private function processMultibanco($orderId, $amount, $request)
    {
        $mbKey = env('IFTHENPAY_MULTIBANCO_KEY', 'CHK-561624');
        $endpointBase = env('IFTHENPAY_MULTIBANCO_ENDPOINT', 'https://api.ifthenpay.com/multibanco/reference');
        $endpoint = $endpointBase . '/init';

        $body = [
            'mbKey'       => $mbKey,
            'orderId'     => $orderId,
            'amount'      => $amount,
            'description' => 'Pagamento MULTIBANCO pedido ' . $orderId,
            'expiryDays'  => 1,
        ];

        $response = Http::post($endpoint, $body);
        $result = $response->json();

        if (isset($result['Status']) && $result['Status'] === '0') {
            $entity     = $result['Entity']   ?? null;
            $reference  = $result['Reference'] ?? null;
            $expiryDate = $result['ExpiryDate'] ?? null;
            $requestId  = $result['RequestId']  ?? null;

            session()->put('multibanco_request_id', $requestId);

            return view('payments.multibanco_reference', [
                'orderId'    => $orderId,
                'amount'     => $amount,
                'entity'     => $entity,
                'reference'  => $reference,
                'expiryDate' => $expiryDate,
            ]);
        } else {
            $message = $result['Message'] ?? 'Erro ao iniciar pagamento por Multibanco.';
            return redirect()->back()->with('error', "Falha MULTIBANCO: $message");
        }
    }

    /**
     * Processa o pagamento via MB WAY.
     */
    private function processMbWay($orderId, $amount, $mbwayPhone, $request)
    {
        if (empty($mbwayPhone)) {
            return redirect()->back()->with('error', 'Informe o telefone MB WAY.');
        }

        $mbWayKey = env('IFTHENPAY_MBWAY_KEY', 'YFC-092716');
        $endpoint = env('IFTHENPAY_MBWAY_ENDPOINT', 'https://api.ifthenpay.com/spg/payment/mbway');

        $body = [
            'mbWayKey'     => $mbWayKey,
            'orderId'      => $orderId,
            'amount'       => $amount,
            'mobileNumber' => $mbwayPhone,
            'email'        => '',
            'description'  => 'Pagamento MBWay pedido ' . $orderId,
        ];

        $response = Http::post($endpoint, $body);
        $result   = $response->json();

        if (isset($result['Status']) && $result['Status'] === '000') {
            $requestId = $result['RequestId'] ?? null;
            session()->put('mbway_request_id', $requestId);

            return view('payments.mbway_initiated', [
                'amount' => $amount,
                'orderId' => $orderId,
            ]);
        } else {
            $message = $result['Message'] ?? 'Erro ao inicializar MBWAY.';
            return redirect()->back()->with('error', "Falha MBWAY: $message");
        }
    }

    /**
     * Processa o pagamento via Cartão de Crédito.
     */
    private function processCreditCard($orderId, $amount, $request)
    {
        $ccardKey     = env('IFTHENPAY_CCARD_KEY');
        $endpointBase = env('IFTHENPAY_CCARD_ENDPOINT', 'https://ifthenpay.com/api/creditcard/init');
        $endpoint     = $endpointBase . "/{$ccardKey}";

        $body = [
            "orderId"    => $orderId,
            "amount"     => $amount,
            "successUrl" => route('payment.success', ['payment_method' => 'card']),
            "errorUrl"   => route('payment.error'),
            "cancelUrl"  => route('payment.cancel'),
            "language"   => "pt"
        ];

        $response = Http::post($endpoint, $body);
        $result   = $response->json();

        if (isset($result['Status']) && $result['Status'] === '0') {
            $paymentUrl = $result['PaymentUrl'] ?? null;
            $requestId  = $result['RequestId'] ?? null;
            session()->put('card_request_id', $requestId);
            return redirect($paymentUrl);
        } else {
            $message = $result['Message'] ?? 'Erro ao iniciar pagamento por cartão.';
            return redirect()->back()->with('error', "Falha CARTÃO: $message");
        }
    }

    /**
     * Exibe a tela de sucesso do pagamento.
     */
    public function success(Request $request)
    {
        $ifthenOrderId = $request->query('id');
        $amount        = $request->query('amount');
        $paymentMethod = $request->query('payment_method');

        $order = Order::where('order_id', $ifthenOrderId)->first();

        if ($order) {
            if ($paymentMethod === 'card') {
                $order->load('meals');
                foreach ($order->meals as $meal) {
                    $quantity = $meal->pivot->quantity;
                    if ($meal->stock >= $quantity) {
                        $meal->decrement('stock', $quantity);
                    } else {
                        \Log::warning("Estoque insuficiente para a refeição ID {$meal->id}.");
                    }
                }
                $order->status         = 'completed';
                $order->payment_status = 'paid';
                $order->save();
                session()->forget('cart');

                return view('payments.success', [
                    'orderId' => $ifthenOrderId,
                    'amount'  => $amount,
                ]);
            } else {
                return view('payments.success', [
                    'orderId' => $ifthenOrderId,
                    'amount'  => $amount,
                ]);
            }
        }

        return redirect()->route('home')->with('error', 'Pedido não encontrado.');
    }

    /**
     * Exibe a view de erro do pagamento.
     */
    public function error(Request $request)
    {
        return view('payments.error');
    }

    /**
     * Exibe a view de cancelamento do pagamento.
     */
    public function cancel(Request $request)
    {
        return view('payments.cancel');
    }

    /**
     * Callback (simulado) para MB WAY.
     */
    public function checkMbWayStatus(Request $request)
{
    // Recupera o orderId da query string
    $orderId = $request->query('orderId');

    // Busca o pedido pelo orderId
    $order = Order::where('order_id', $orderId)->first();

    if (!$order) {
        return redirect()->route('home')->with('error', 'Pedido não encontrado.');
    }

    // Exemplo de verificação do status do pagamento:
    // Se o pagamento já foi confirmado, exibe a view de sucesso;
    // caso contrário, exibe a view de pendência.
    if ($order->payment_status === 'paid') {
        return view('payments.success', [
            'orderId' => $orderId,
            'amount'  => $order->amount
        ]);
    } else {
        return view('payments.mbway_pending', [
            'orderId' => $orderId
        ]);
    }
}



    public function mbwayCallback(Request $request)
    {
        return response('OK', 200);
    }
}
