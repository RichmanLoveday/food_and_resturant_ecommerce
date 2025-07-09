<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DailyOfferDataTable;
use App\Http\Controllers\Controller;
use App\Models\DailyOffer;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyOfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DailyOfferDataTable $datatable): View|JsonResponse
    {
        return $datatable->render('admin.daily-offer.index');
    }


    public function productSearch(Request $request): JsonResponse
    {
        $request->validate([
            'search' => 'required|string|min:1|max:255',
        ]);

        //? Search for products by name
        $product = Product::select('id', 'name', 'thumb_image')
            ->where('name', 'LIKE', '%' . $request->search . '%')
            ->get();

        return response()->json($product, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.daily-offer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product' => ['required', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        $offer = new DailyOffer();
        $offer->product_id = $request->product;
        $offer->status = $request->status;
        $offer->save();

        toastr()->success('Created Successfully');
        return redirect()->route('admin.daily-offer.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
