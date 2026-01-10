<?php

namespace App\Http\Controllers\Frontend;

use App\Events\OrderPaymentUpdateEvent;
use App\Events\RTOrderPlacedNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Razorpay\Api\Api as RazorpayApi;

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


    public function paymentSuccess()
    {
        $breadCrumb = ['title' => 'order', 'link' => '#'];
        return view('frontend.pages.payment-success', compact('breadCrumb'));
    }

    public function paymentCancel()
    {
        $breadCrumb = ['title' => 'order', 'link' => '#'];
        return view('frontend.pages.payment-cancel', compact('breadCrumb'));
    }

    public function paymentFail()
    {
        $breadCrumb = ['title' => 'order', 'link' => '#'];
        return view('frontend.pages.payment-issue', compact('breadCrumb'));
    }


    public function makePayment(Request $request, OrderService $orderService)
    {
        // dd($request->all());
        $request->validate([
            'payment_gateway' => ['required', 'string', 'in:paypal,stripe,razorpay']
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


                    case 'stripe':
                        //? redirect to paypal payment
                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => route('stripe.payment'),
                        ]);

                    case 'razorpay':
                        //? redirect to razorpay payment
                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => route('razorpay-redirect'),
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

    /** Paypal Payment methods */

    private function setPaypalConfig(): array
    {
        $config = [
            'mode'    => config('gatewaySettings.paypal_account_mode'), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
            'sandbox' => [
                'client_id'         => config('gatewaySettings.paypal_api_key'),
                'client_secret'     => config('gatewaySettings.paypal_secret_key'),
                'app_id'            => config('gatewaySettings.paypal_app_id'),
            ],
            'live' => [
                'client_id'         => config('gatewaySettings.paypal_api_key'),
                'client_secret'     => config('gatewaySettings.paypal_secret_key'),
                'app_id'            => config('gatewaySettings.paypal_app_id'),
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
        try {
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
            } else {
                $this->transactionFailedUpdateStatus('PayPal');
                return redirect()->route('payment.cancel')
                    ->withErrors(['errors' => $response['error']['message']]);
            }
        } catch (\Exception $e) {
            logger("PayPal Payment Error: " . $e->getMessage());

            return redirect()->route('payment.cancel')
                ->withErrors(['errors' => 'Something went wrong!']);
        }
    }

    public function paypalSuccess(Request $request, OrderService $orderService)
    {
        $config = $this->setPaypalConfig();
        $provider = new PayPalClient($config);
        $provider->getAccessToken();

        //? verify the payment token 
        $response = $provider->capturePaymentOrder($request->token);

        if (!isset($response['status']) || $response['status'] !== 'COMPLETED') {
            $this->transactionFailedUpdateStatus('PayPal');

            return redirect()->route('payment.cancel')
                ->withErrors(['errors' => $response['error']['message'] ?? 'Payment failed']);
        }

        //? Extract capture info
        $capture = $response['purchase_units'][0]['payments']['captures'][0];

        //? safely resolve order
        $orderId = $response['purchase_units'][0]['custom_id']
            ?? session()->get('order_id');

        $order = Order::with('orderItems')->findOrFail($orderId);

        //? Prevent double execution
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success');
        }


        try {
            DB::transaction(function () use ($orderId, $order, $orderService, $capture) {
                //? deduct stock or product quantity safely
                $orderService->deductProductQuantities($order->orderItems);
            });
        } catch (\Exception $e) {

            //? Payment succeeded but stock failed
            Log::critical('Stock failure after PayPal payment', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('payment.fail')
                ->withErrors([
                    'errors' => 'Payment received but product is out of stock. Admin has been notified.'
                ]);
        }

        //? update order informations
        $paymentInfo = [
            'transaction_id' => $capture['id'],
            'currency' => $capture['amount']['currency_code'],
            'status' => 'completed',
        ];

        //? fire order payment update event
        event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'PayPal'));

        //? fire event for real time notification
        event(new RTOrderPlacedNotificationEvent(Order::find($orderId)));

        //? clear session data
        $orderService->clearSession();

        return redirect()->route('payment.success');



        // if (isset($response['status']) && $response['status'] == 'COMPLETED') {
        //     // dd($response);

        //     //? payment successful, update order status and save payment info
        //     $capture = $response['purchase_units'][0]['payments']['captures'][0];

        //     $orderId = session()->get('order_id');
        //     $paymentInfo = [
        //         'transaction_id' => $capture['id'],
        //         'currency' => $capture['amount']['currency_code'],
        //         'status' => 'completed',
        //     ];

        //     //? fire order payment update event
        //     event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'PayPal'));

        //     //? fire event for real time notification
        //     event(new RTOrderPlacedNotificationEvent(Order::find($orderId)));

        //     //? clear session data
        //     $orderService->clearSession();

        //     return redirect()->route('payment.success');
        // } else {
        //     $this->transactionFailedUpdateStatus('PayPal');
        //     //? redirect user to error page if any error is encountered
        //     return redirect()->route('payment.cancel')
        //         ->withErrors(['errors' => $response['error']['message']]);
        // }
    }

    public function paypalCancel()
    {
        $this->transactionFailedUpdateStatus('Paypal');
        return redirect()->route('payment.cancel');
    }


    /** Stripe Payment Methods **/
    public function paywithStripe()
    {
        try {
            /** Calulate payable amount */
            $grandTotal = session()->get('grand_total');
            $usdAmount = ($grandTotal * config('gatewaySettings.stripe_rate')) * 100;      // $10 * 100 = 1000
            // dd($usdAmount);

            if ($usdAmount < 0.5) {
                return back()->withErrors([
                    'erros' => 'Minimum payment amount is ₦' . number_format(0.5 / config('gatewaySettings.stripe_rate'), 2)
                ]);

                $this->transactionFailedUpdateStatus('Stripe');

                return redirect()->route('payment.cancel')
                    ->withErrors(['errors' =>  'Minimum payment amount is ' . currencyPosition(number_format(0.5 / config('gatewaySettings.stripe_rate'), 2))]);
            }

            //? payable amount in cents
            $payableAmount = $usdAmount;

            Stripe::setApiKey(config('gatewaySettings.stripe_secret_key'));
            $response = StripeSession::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => config('gatewaySettings.stripe_currency'),
                        'product_data' => [
                            'name' => 'Order Payment',
                        ],
                        'unit_amount' => $payableAmount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel'),
            ]);

            //? if response is successfull and has an id
            if (isset($response->id) && $response->id != null) {
                //? redirect to stripe payment page
                return redirect()->away($response->url);
            } else {
                $this->transactionFailedUpdateStatus('Stripe');
                return redirect()->route('payment.cancel')
                    ->withErrors(['errors' => 'Something went wrong!']);
            }
        } catch (\Exception $e) {
            logger("Stripe Payment Error: " . $e->getMessage());
            return redirect()->route('payment.cancel')
                ->withErrors(['errors' => 'Something went wrong!']);
        }
    }


    public function stripeSuccess(Request $request, OrderService $orderService)
    {
        $sessionId = $request->session_id;
        Stripe::setApiKey(config('gatewaySettings.stripe_secret_key'));

        $response = StripeSession::retrieve($sessionId);

        if ($response->payment_status !== 'paid') {
            $this->transactionFailedUpdateStatus('Stripe');

            return redirect()->route('payment.cancel')
                ->withErrors(['errors' => 'Payment failed!']);
        }


        //? safely resolve order
        $orderId = session()->get('order_id');

        $order = Order::with('orderItems')->findOrFail($orderId);

        //? Prevent double execution
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success');
        }


        try {
            DB::transaction(function () use ($orderId, $order, $orderService, $response) {
                //? deduct stock or product quantity safely
                // dd($order);
                $orderService->deductProductQuantities($order->orderItems);
            });
        } catch (\Exception $e) {
            //? Payment succeeded but stock failed
            Log::critical('Stock failure after PayPal payment', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('payment.fail')
                ->withErrors([
                    'errors' => 'Payment received but product is out of stock. Admin has been notified.'
                ]);
        }

        //? update order informations
        $paymentInfo = [
            'transaction_id' => $response->payment_intent,
            'currency' => $response->currency,
            'status' => 'completed',
        ];


        //? fire order payment update event
        event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'PayPal'));

        //? fire event for real time notification
        event(new RTOrderPlacedNotificationEvent(Order::find($orderId)));

        //? clear session data
        $orderService->clearSession();

        return redirect()->route('payment.success');


        // dd($response);
        // if ($response->payment_status === 'paid') {
        //     //? payment successful, update order status and save payment info
        //     $orderId = session()->get('order_id');
        //     $paymentInfo = [
        //         'transaction_id' => $response->payment_intent,
        //         'currency' => $response->currency,
        //         'status' => 'completed',
        //     ];

        //     //? fire order payment update event
        //     event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'Stripe'));

        //     //? fire event for real time notification
        //     event(new RTOrderPlacedNotificationEvent(Order::find($orderId)));

        //     //? clear session data
        //     $orderService->clearSession();

        //     return redirect()->route('payment.success');
        // } else {
        //     $this->transactionFailedUpdateStatus('Stripe');

        //     //? redirect user to error page if any error is encountered
        //     return redirect()->route('payment.cancel')
        //         ->withErrors(['errors' => 'Payment failed!']);
        // }
    }


    public function stripeCancel()
    {
        $this->transactionFailedUpdateStatus('Stripe');
        return redirect()->route('payment.cancel');
    }


    public function paywithRazorpayRedirect()
    {
        return view('frontend.pages.razorpay-redirect');
    }

    // public function paywithRazorpay(Request $request, OrderService $orderService)
    // {
    //     // dd($request->all());
    //     /** Calulate payable amount */
    //     $grand_total = session()->get('grand_total');
    //     $payableAmount = $grand_total * config('gatewaySettings.razorpay_rate');
    //     $payableAmount = $payableAmount * 100;

    //     // dd($payableAmount);

    //     //? check if razorpay api key and secret key is set
    //     if (!config('gatewaySettings.razorpay_api_key') || !config('gatewaySettings.razorpay_secret_key')) {
    //         return redirect()->route('payment.cancel')
    //             ->withErrors(['errors' => 'Razorpay payment gateway is not configured!']);
    //     }

    //     $api = new RazorpayApi(
    //         config('gatewaySettings.razorpay_api_key'),
    //         config('gatewaySettings.razorpay_secret_key')
    //     );

    //     //? check if razorpay payment id is present
    //     if ($request->has('razorpay_payment_id') && $request->filled('razorpay_payment_id')) {
    //         try {
    //             $response = $api->payment
    //                 ->fetch($request->razorpay_payment_id)
    //                 ->capture(['amount' => $payableAmount]);

    //             if ($response['status'] === 'captured') {
    //                 //? payment successful, update order status and save payment info
    //                 $orderId = session()->get('order_id');
    //                 $paymentInfo = [
    //                     'transaction_id' => $response['id'],
    //                     'currency' => $response['currency'],
    //                     'status' => 'completed',
    //                 ];

    //                 //? fire order payment update event
    //                 event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'Razorpay'));

    //                 //? fire event for real time notification
    //                 event(new RTOrderPlacedNotificationEvent(Order::find($orderId)));

    //                 //? clear session data
    //                 $orderService->clearSession();

    //                 return redirect()->route('payment.success');
    //             } else {
    //                 $this->transactionFailedUpdateStatus('Razorpay');
    //                 return redirect()->route('payment.cancel')
    //                     ->withErrors(['errors' => 'Payment failed!']);
    //             }
    //             // dd($response);
    //         } catch (\Exception $e) {
    //             logger("Razorpay payment error: " . $e->getMessage());
    //             $this->transactionFailedUpdateStatus('Razorpay'); //? update order status to failed
    //             return redirect()->route('payment.cancel')
    //                 ->withErrors(['errors' => $e->getMessage()]);
    //         }
    //     }
    // }



    public function paywithRazorpay(Request $request, OrderService $orderService)
    {
        //? Validate Razorpay config
        if (
            !config('gatewaySettings.razorpay_api_key') ||
            !config('gatewaySettings.razorpay_secret_key') ||
            !config('gatewaySettings.razorpay_rate')
        ) {
            return redirect()->route('payment.cancel')
                ->withErrors(['errors' => 'Razorpay payment gateway is not configured!']);
        }

        // 2️⃣ Resolve order SAFELY
        // BEST: pass order_id as Razorpay "notes"
        $orderId = session()->get('order_id');
        $order = Order::with('orderItems')->findOrFail($orderId);

        //? Prevent double execution
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success');
        }

        /** Calulate payable amount */
        $grand_total = session()->get('grand_total');
        $payableAmount = $grand_total * config('gatewaySettings.razorpay_rate');
        $payableAmount = $payableAmount * 100;

        $api = new RazorpayApi(
            config('gatewaySettings.razorpay_api_key'),
            config('gatewaySettings.razorpay_secret_key')
        );


        if (!$request->filled('razorpay_payment_id')) {
            return redirect()->route('payment.cancel')
                ->withErrors(['errors' => 'Invalid Razorpay payment']);
        }

        try {
            //? Capture payment
            $response = $api->payment
                ->fetch($request->razorpay_payment_id)
                ->capture(['amount' => $payableAmount]);

            if ($response['status'] !== 'captured') {
                throw new \Exception('Razorpay payment capture failed');
            }

            //? Finalize order (atomic)
            DB::transaction(function () use ($order, $orderService, $response) {
                //? deduct stock or product quantity safely
                $orderService->deductProductQuantities($order->orderItems);
            });
        } catch (\Exception $e) {

            Log::critical('Razorpay payment error', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('payment.fail')
                ->withErrors([
                    'errors' => 'Payment was successful but stock could not be confirmed. Admin has been notified.'
                ]);
        }

        $paymentInfo = [
            'transaction_id' => $response['id'],
            'currency' => $response['currency'],
            'status' => 'completed',
        ];

        //? fire order payment update event
        event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, 'Razorpay'));

        //? fire event for real time notification
        event(new RTOrderPlacedNotificationEvent(Order::find($orderId)));

        //? clear session data
        $orderService->clearSession();

        return redirect()->route('payment.success');
    }


    private function transactionFailedUpdateStatus(string $gatewayName)
    {
        $orderId = session()->get('order_id');
        $paymentInfo = [
            'transaction_id' => "",
            'currency' => "",
            'status' => 'failed',
        ];

        //? fire order payment update event
        event(new OrderPaymentUpdateEvent($orderId, $paymentInfo, $gatewayName));
    }
}
