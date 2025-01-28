<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function process(Request $request)
    {
        // Construímos um array de regras.
        // Vamos sempre exigir 'items.*.pickup_time' e 'payment_method'.
        $rules = [
            'items'                    => 'required|array',
            'items.*.pickup_time'      => 'required|string',
            'payment_method'           => 'required|string',
        ];

        // Mensagens personalizadas
        $messages = [
            'items.*.pickup_time.required' => 'Por favor selecione o horário de pickup para cada refeição.',
            'payment_method.required'      => 'Por favor selecione um método de pagamento.',
            'payment_method.string'        => 'Método de pagamento inválido.',
        ];

        // Se o usuário NÃO estiver logado, então exigimos nome e email
        if (!Auth::check()) {
            $rules['customer_name']  = 'required|string';
            $rules['customer_email'] = 'required|email';
            
            $messages['customer_name.required']   = 'Por favor insira seu nome.';
            $messages['customer_email.required']  = 'Por favor insira seu e-mail.';
            $messages['customer_email.email']     = 'Por favor insira um e-mail válido.';
        }

        // 1) Valida os campos de acordo com as regras (se falhar, redireciona com erros)
        $request->validate($rules, $messages);

        // 2) Pegar método de pagamento
        $paymentMethod = $request->input('payment_method');

        // 3) Pegar carrinho
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Carrinho está vazio!');
        }

        // 4) Calcular total
        $amount = 0;
        foreach ($cart as $id => $item) {
            $amount += ($item['price'] * $item['quantity']);
        }
        $amount = number_format($amount, 2, '.', '');

        // 5) Criar orderId p/ IfthenPay (exemplo)
        $ifthenOrderId = 'ORD' . rand(1000, 9999);

        // 6) Criar pedido (status pending)
        $order = new Order();
        $order->order_id       = $ifthenOrderId;
        $order->amount         = $amount;
        $order->payment_method = $paymentMethod;
        $order->status         = 'pending';
        $order->payment_status = 'pending';

        // Se estiver logado, pegar do Auth; caso contrário, usar o input
        if (Auth::check()) {
            $order->user_id        = Auth::id();
            $order->customer_name  = Auth::user()->name;
            $order->customer_email = Auth::user()->email;
        } else {
            $order->customer_name  = $request->input('customer_name');
            $order->customer_email = $request->input('customer_email');
        }

        // 7) (Opcional) Salvar descrição extra
        if ($request->input('add_order_info') === 'yes') {
            $order->order_description = $request->input('order_description');
        }

        $order->save();

        // 8) Associar itens do carrinho na pivot + incluir pickup_time e note
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

        // 9) Chamar IfthenPay (ou outro gateway)
        try {
            switch ($paymentMethod) {
                case 'multibanco':
                    return $this->processMultibanco($ifthenOrderId, $amount);

                case 'mbway':
                    $mbwayPhone = $request->input('mbway_phone');
                    return $this->processMbWay($ifthenOrderId, $amount, $mbwayPhone);

                case 'card':
                    return $this->processCreditCard($ifthenOrderId, $amount);

                default:
                    return redirect()->back()->with('error', 'Método de pagamento não suportado.');
            }
        } catch (\Exception $e) {
            \Log::error("Erro no pagamento: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ocorreu um erro ao processar o pagamento.');
        }
    }

    private function processMultibanco($orderId, $amount)
    {
        return redirect()->back()->with('success', 'Pagamento MULTIBANCO gerado (exemplo).');
    }

    private function processMbWay($orderId, $amount, $mbwayPhone)
    {
        if (empty($mbwayPhone)) {
            return redirect()->back()->with('error', 'Informe o telefone MB WAY.');
        }

        $mbWayKey = env('IFTHENPAY_MBWAY_KEY');
        $endpoint = env('IFTHENPAY_MBWAY_ENDPOINT');

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

            return redirect()->back()->with('success', 'MBWAY iniciado! Verifique a app MBWAY.');
        } else {
            $message = $result['Message'] ?? 'Erro ao inicializar MBWAY.';
            return redirect()->back()->with('error', "Falha MBWAY: $message");
        }
    }

    private function processCreditCard($orderId, $amount)
    {
        $ccardKey     = env('IFTHENPAY_CCARD_KEY');
        $endpointBase = env('IFTHENPAY_CCARD_ENDPOINT'); 
        $endpoint     = $endpointBase . "/{$ccardKey}";

        $body = [
            "orderId"    => $orderId,
            "amount"     => $amount,
            "successUrl" => route('payment.success'),
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

            // Redirecionar para página da IfthenPay (dados de cartão)
            return redirect($paymentUrl);
        } else {
            $message = $result['Message'] ?? 'Erro ao iniciar pagamento por cartão.';
            return redirect()->back()->with('error', "Falha CARTÃO: $message");
        }
    }

    public function success(Request $request)
    {
        $ifthenOrderId = $request->query('id');
        $order = Order::where('order_id', $ifthenOrderId)->first();
        if ($order) {
            $order->status         = 'completed';
            $order->payment_status = 'paid';
            $order->save();
            session()->forget('cart');
        }

        return view('payments.success', [
            'orderId' => $ifthenOrderId,
            'amount'  => $request->query('amount'),
        ]);
    }

    public function error(Request $request)
    {
        return view('payments.error');
    }

    public function cancel(Request $request)
    {
        return view('payments.cancel');
    }

    // (opcional) Callback MBWAY, se precisar
    public function mbwayCallback(Request $request)
    {
        return response('OK', 200);
    }
}

