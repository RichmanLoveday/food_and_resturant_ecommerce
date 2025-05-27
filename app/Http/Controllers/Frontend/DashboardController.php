<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AddressCreateRequest;
use App\Models\Address;
use App\Models\DeliveryArea;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): View
    {
        $deliveryAreas = DeliveryArea::where('status', true)->get();
        return view('frontend.dashboard.index', compact('deliveryAreas'));
    }


    public function createAddress(AddressCreateRequest $request)
    {
        $address = new Address();
        $address->delivery_area_id = $request->area;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        // $address->email = $
    }
}