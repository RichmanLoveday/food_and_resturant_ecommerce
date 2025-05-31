<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

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
        $request->validate([
            'payment_gateway' => ['required', 'string', 'in:paypal']
        ]);

        //? create order
        try {
            $orderService->createOrder();
        } catch (\Exception $e) {
            throw $e;
        }
    }
}