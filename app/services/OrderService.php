<?php

namespace App\services;

use App\Models\Order;
use Auth;
use Cart;

class OrderService
{
    /** Store Order in Database */
    public function createOrder()
    {
        $order = new Order();
        $order->invoice_id = generateInvoiceId();
        $order->user_id = Auth::user()->id;
        $order->address = session()->get('address');
        $order->discount = session()->get('coupon')['discount'] ?? 0;
        $order->delivery_charge = session()->get('delivery_charge');
        $order->subtotal = cartTotal();
        $order->grand_total = grandCartTotal(session()->get('delivery_charge'));
        $order->product_qty = Cart::content()->count();
        $order->payment_method = NULL;
        $order->payment_status = 'pending';
        $order->payment_approve_date = NULL;
        $order->transaction_id = NULL;
        $order->coupon_info = session()->get('coupon') ? json_encode(session()->get('coupon')) : Null;
        $order->currency_name = NULL;
        $order->order_status = 'pending';
        $order->save();
    }

    /** Clear Session Items */
    public function clearSession() {}
}