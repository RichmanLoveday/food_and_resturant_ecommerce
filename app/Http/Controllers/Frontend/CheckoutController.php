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

        //? check if user has selected address in session
        // if (session()->has('address')) {
        //     //? get user selected address
        //     $selectedAddress = Address::with('deliveryArea')->findOrFail(session()->get('address'));

        //     //? restore delivery area fee in session
        //     session()->put('delivery_charge', $selectedAddress->deliveryArea?->delivery_fee);
        // }
        // dd($deliveryAreas);

        return view('frontend.pages.check-out', compact('breadCrumb', 'addresses', 'deliveryAreas'));
    }


    /**
     * Calculate the delivery charge and grand total for a given address.
     *
     * @param string|int $id Address ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDeliveryCharge(string|int $id)
    {
        try {
            $address = Address::findOrFail($id);
            $deliveryAmount = $address->deliveryArea?->delivery_fee;
            $grandTotal = grandCartTotal($deliveryAmount);

            return response()->json(['delivery_fee' => $deliveryAmount, 'grand_total' => $grandTotal]);
        } catch (\Exception $e) {
            logger($e);
            return response()->json(['message' => 'something went wrong!'], 422);
        }
    }


    public function checkoutRedirect(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer'],
        ]);

        //? get user user selected address, and delivery area relation
        $address = Address::with('deliveryArea')->findOrFail($request->id);
        $selectedAddress = "{$address->address} Area: {$address->deliveryArea?->area_name}";

        //? store address in a session
        session()->put('address', $selectedAddress);
        session()->put('delivery_charge', $address->deliveryArea?->delivery_fee);

        return response()->json(['redirect_url' => route('payment.index')]);
    }
}