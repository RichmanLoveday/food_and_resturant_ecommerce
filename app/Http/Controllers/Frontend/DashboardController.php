<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AddressCreateRequest;
use App\Http\Requests\Frontend\AddressUpdateRequest;
use App\Models\Address;
use App\Models\DeliveryArea;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): View
    {
        $deliveryAreas = DeliveryArea::where('status', true)->get();
        $userAddresses = Address::with(['deliveryArea'])
            ->where('user_id', Auth::user()->id)->get();

        // dd($userAddresses->toArray());

        return view('frontend.dashboard.index', compact('deliveryAreas', 'userAddresses'));
    }


    public function createAddress(AddressCreateRequest $request)
    {
        // dd($request->all());
        $address = new Address();
        $address->user_id = Auth::user()->id;
        $address->delivery_area_id = $request->area;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->type = $request->type;
        $address->save();

        toastr()->success('Created Successfully');

        return redirect()->back();
    }

    public function updateAddress(string $id, AddressUpdateRequest $request)
    {
        $address = Address::findOrFail($id);
        $address->user_id = Auth::user()->id;
        $address->delivery_area_id = $request->area;
        $address->first_name = $request->first_name;
        $address->last_name = $request->last_name;
        $address->email = $request->email;
        $address->phone = $request->phone;
        $address->address = $request->address;
        $address->type = $request->type;
        $address->save();

        toastr()->success('Updated Successfully');

        return redirect()->back();
    }


    public function destroyAddress(string $id)
    {
        $address = Address::find($id);

        if ($address && $address->user_id === Auth::user()->id) {
            $address->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'something went wrong!',
        ]);
    }
}
