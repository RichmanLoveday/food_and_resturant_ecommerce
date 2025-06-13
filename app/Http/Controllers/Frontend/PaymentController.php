<?php

namespace App\Http\Controllers\Frontend;

use App\Events\OrderPaymentUpdateEvent;
use App\Http\Controllers\Controller;
use App\services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    public function index(): View
    {
        //? check if delivery fee and address exist
        if (!session()->has('delivery_charge') || !session()->has('address')) {
            throw ValidationException::withMessages(['Something went wrong!']);
        }

        $subTotal = cartTotal();
        $deliveryCharge = session()->get('delivery_charge') ?? 0;
        $discount = session()->get('coupon')['discount'] ?? 0;
        $grandTotal = grandCartTotal($deliveryCharge);

        $breadCrumb = ['title' => 'payment', 'link' => '#'];
        return view('frontend.pages.payment', compact(
            'breadCrumb',
            'subTotal',
            'deliveryCharge',
            'discount',
            'grandTotal'
        ));
    }


    public function makePayment(Request $request, OrderService $orderService)
    {
        // dd($request->all());
        $request->validate([
            'payment_gateway' => ['required', 'string', 'in:paypal']
        ]);

        //? create order
        try {
            if ($orderService->createOrder()) {
                //? check for type of payment gateway used
                switch ($request->payment_gateway) {
                    case 'paypal':
                        //? redirect to paypal payment
                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => route('paypal.payment'),
                        ]);
                    default:
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Unsupported payment gateway.',
                        ], 400);
                }
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Order creation failed.',
            ], 500);
        } catch (\Exception $e) {
            throw $e;
        }
    }


    private function setPaypalConfig(): array
    {
        $config = [
            'mode'    => config('gatewaySettings.paypal_account_mode'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => config('gatewaySettings.paypal_api_key'),
                'client_secret'     => config('gatewaySettings.paypal_secret_key'),
                'app_id'            => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'         => config('gatewaySettings.paypal_api_key'),
                'client_secret'     => config('gatewaySettings.paypal_secret_key'),
                'app_id'            => env('PAYPAL_LIVE_APP_ID', ''),
            ],

            'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
            'currency'       => config('gatewaySettings.paypal_currency'),
            'notify_url'     => env('PAYPAL_NOTIFY_URL', ''), // Change this accordingly for your application.
            'locale'         => 'en_US', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
            'validate_ssl'   => true, // Validate SSL when creating api client.
        ];

        return $config;
    }


    public function paywithPaypal()
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        /** Calulate payable amount */
        $grandTotal = session()->get('grand_total');
        $payableAmount = round($grandTotal) * config('gatewaySettings.paypal_rate');

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.success'),
                "cancel_url" => route('paypal.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => config("gatewaySettings.paypal_currency"),
                        "value" => $payableAmount,
                    ]
                ]
            ]
        ]);


        //? if response is successfull and has an id
        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    //? redirect to paypal payment page
                    return redirect()->away($link['href']);
                }
            }
        }
    }

    public function paypalSuccess(Request $request)
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        //? verify the payment token 
        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // dd($response);

            //? payment successful, update order status and save payment info
            $capture = $response['purchase_units'][0]['payments']['captures'][0];

            $orderId = session()->get('order_id');
            $paymentInfo = [
                'transaction_id' => $capture['id'],
                'currency' => $capture['amount']['currency_code'],
                'status' => $capture['status'],
            ];

            //? fire order payment update event
            event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'PayPal'));

            dd('success');
        }
    }

    public function paypalCancel() {}
}