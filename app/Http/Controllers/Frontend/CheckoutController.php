<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use Auth;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $breadCrumb = ['title' => 'check out', 'link' => '#'];
        $addresses = Address::where(['user_id' => Auth::user()->id])->get();
        $deliveryAreas = DeliveryArea::where('status', true)->get();

        // dd($deliveryAreas);

        return view('frontend.pages.check-out', compact('breadCrumb', 'addresses', 'deliveryAreas'));
    }


    public function calculateDeliveryCharge(string|int $id)
    {
        try {
            $address = Address::findOrFail($id);
            $deliveryAmount = $address->deliveryArea?->delivery_fee;
            $grandTotal = grandCartTotal() + $deliveryAmount;

            return response()->json(['delivery_fee' => $deliveryAmount, 'grand_total' => $grandTotal]);
        } catch (\Exception $e) {
            logger($e);
            return response()->json(['message' => 'something went wrong!'], 422);
        }
    }
}