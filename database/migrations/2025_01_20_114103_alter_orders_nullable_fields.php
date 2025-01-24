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
        $paymentMethod = $request->input('payment_method');

        // Capturar nome/email do formulário (caso seja guest)
        $customerName  = $request->input('customer_name');
        $customerEmail = $request->input('customer_email');

        // Recuperar carrinho
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Seu carrinho está vazio!');
        }

        // Calcular total
        $amount = 0;
        foreach ($cart as $id => $item) {
            $amount += ($item['price'] * $item['quantity']);
        }
        $amount = number_format($amount, 2, '.', '');

        // Gera 'orderId' p/ IfthenPay
        $ifthenOrderId = 'ORD' . rand(1000, 9999);

        // Criar o pedido (status 'pending')
        $order = new Order();
        $order->order_id       = $ifthenOrderId;
        $order->amount         = $amount;
        $order->payment_method = $paymentMethod;
        $order->status         = 'pending';
        $order->payment_status = 'pending';
        $order->user_id        = Auth::id(); // se logado, senão null
        $order->customer_name  = $customerName;
        $order->customer_email = $customerEmail;
        $order->save();

        // Associar as meals na pivot 'order_meal'
        foreach ($cart as $mealId => $item) {
            $quantity  = $item['quantity'];
            // Se você armazenou day_of_week no array do cart:
            $dayOfWeek = $item['day_of_week'] ?? null;

            $order->meals()->attach($mealId, [
                'quantity'    => $quantity,
                'day_of_week' => $dayOfWeek,
            ]);
        }

        // Chamada do IfthenPay
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
        // Lógica de gerar referência MB...
        return redirect()->back()->with('success', 'Pagamento MULTIBANCO gerado (exemplo).');
    }

    private function processMbWay($orderId, $amount, $mbwayPhone)
    {
        if (empty($mbwayPhone)) {
            return redirect()->back()->with('error', 'É necessário informar o telefone MB WAY.');
        }

        $mbWayKey = env('IFTHENPAY_MBWAY_KEY');
        $endpoint = env('IFTHENPAY_MBWAY_ENDPOINT'); // ex.: https://api.ifthenpay.com/spg/payment/mbway

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

            return redirect()->back()->with('success', 'MBWAY iniciado! Verifique a app MBWAY para confirmar.');
        } else {
            $message = $result['Message'] ?? 'Erro ao inicializar MBWAY.';
            return redirect()->back()->with('error', "Falha MBWAY: $message");
        }
    }

    private function processCreditCard($orderId, $amount)
    {
        $ccardKey    = env('IFTHENPAY_CCARD_KEY');
        $endpointBase= env('IFTHENPAY_CCARD_ENDPOINT');
        $endpoint    = $endpointBase . "/{$ccardKey}";

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

            // Redirecionar p/ página do cartão da IfthenPay
            return redirect($paymentUrl);
        } else {
            $message = $result['Message'] ?? 'Erro ao inicializar pagamento por cartão.';
            return redirect()->back()->with('error', "Falha CARTÃO: $message");
        }
    }

    public function success(Request $request)
    {
        // IfthenPay deve redirecionar com ?id=...&amount=...&requestId=...
        $ifthenOrderId = $request->query('id');  

        // Localizar o pedido
        $order = Order::where('order_id', $ifthenOrderId)->first();
        if ($order) {
            // Atualizar status
            $order->status = 'completed';
            $order->payment_status = 'paid';
            $order->save();

            // Limpar carrinho da sessão
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

    // (Opcional) Callback MBWAY (webhook)
    public function mbwayCallback(Request $request)
    {
        // ...
        return response('OK', 200);
    }
}
