<?php

namespace App\Listeners;

use App\Events\OrderPaymentUpdateEvent;
use App\Mail\OrderPlacedMail;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class OrderPlacedNotificationEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPaymentUpdateEvent $event): void
    {
        $orderId = $event->orderId;
        $order = Order::with(['user'])->find($orderId);

        //? send order mail
        Mail::send(new OrderPlacedMail($order));
    }
}
