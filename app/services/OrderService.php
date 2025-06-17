<?php

namespace App\services;

use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Cart;
use DB;
use Log;

class OrderService
{
    /** Store Order in Database */

    public function createOrder()
    {
        try {
            DB::beginTransaction();

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
            $order->address_id = session()->get('address_id');

            $saved = $order->save();

            //? Add order items to order items table
            foreach (Cart::content() as $product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->product_name = $product->name;
                $orderItem->qty = $product->qty;
                $orderItem->unit_price = $product->price;
                $orderItem->product_size = json_encode($product->options->product_size);
                $orderItem->product_option = json_encode($product->options->product_options);
                $orderItem->save();
            }

            DB::commit();

            //? Saving the grand total amount and order id in session 
            session()->put('grand_total', $order->grand_total);
            session()->put('order_id', $order->id);

            return $saved;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating order: ' . $e->getMessage());
            return false;
        }
    }

    /** Clear Session Items */
    public function clearSession()
    {
        Cart::destroy();
        session()->forget([
            'address',
            'delivery_charge',
            'address_id',
            'coupon',
            'grand_total',
            'order_id'
        ]);
    }
}