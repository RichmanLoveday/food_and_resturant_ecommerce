<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\AddressCreateRequest;
use App\Http\Requests\Frontend\AddressUpdateRequest;
use App\Models\Address;
use App\Models\DeliveryArea;
use App\Models\Order;
use App\Models\ProductRating;
use App\Models\Reservation;
use App\Models\Wishlist;
use Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(): View
    {
        $breadCrumb = ['title' => 'User Dashboard', 'link' => '#'];
        $deliveryAreas = DeliveryArea::where('status', true)->get();
        $userAddresses = Address::with(['deliveryArea'])
            ->where('user_id', Auth::user()->id)->get();
        $orders = Order::with(['user', 'userAddress'])
            ->where('user_id', Auth::user()->id)
            ->get();
        $reservations = Reservation::where('user_id', Auth::user()->id)
            ->latest()
            ->get();
        $reviews = ProductRating::where('user_id', Auth::user()->id)
            ->latest()
            ->paginate(1);

        $wishlist = Wishlist::where(['user_id' =>  Auth::user()->id])->latest()->get();
        $totalOrders = Order::where('user_id', Auth::user()->id)->count();
        $totalCompleteOrders = Order::where([
            'user_id' => Auth::user()->id,
            'order_status' => 'delivered'
        ])->count();
        $totalCancelledOrders = Order::where([
            'user_id' => Auth::user()->id,
            'order_status' => 'declined'
        ])->count();

        // dd($userAddresses->toArray());

        return view('frontend.dashboard.index', compact(
            'breadCrumb',
            'deliveryAreas',
            'userAddresses',
            'orders',
            'reservations',
            'reviews',
            'wishlist',
            'totalOrders',
            'totalCompleteOrders',
            'totalCancelledOrders',
        ));
    }


    public function loadMoreUserReviews()
    {
        try {
            // dd($productId);
            $reviews = ProductRating::where('user_id', Auth::user()->id)
                ->latest()
                ->paginate(1);

            // dd($reviews);
            return view('frontend.layout.ajax-files.user-reviews', compact('reviews'));
        } catch (\Exception $e) {
            logger('Error loading more reviews: ' . $e->getMessage());
            return response()->json([
                'message' => 'Unable to load reviews at this time.',
            ], 500);
        }
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