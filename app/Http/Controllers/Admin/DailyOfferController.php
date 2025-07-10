<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DailyOfferDataTable;
use App\Http\Controllers\Controller;
use App\Models\DailyOffer;
use App\Models\Product;
use App\Models\SectionTitle;
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
        $dailyOffer = DailyOffer::with('product')->findOrFail($id);
        return view('admin.daily-offer.edit', compact('dailyOffer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product' => ['required', 'integer'],
            'status' => ['required', 'boolean'],
        ]);

        $offer = DailyOffer::findOrFail($id);
        $offer->product_id = $request->product;
        $offer->status = $request->status;
        $offer->save();

        toastr()->success('Updated Successfully');
        return redirect()->route('admin.daily-offer.index');
    }


    /**
     * Update the title for the daily offer section.
     * This method updates the section titles for the daily offer page.
     */
    public function updateTitle(Request $request)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'daily_offer_top_title' => 'max:255',
            'daily_offer_main_title' => 'max:255',
            'daily_offer_sub_title' => 'max:255',
        ]);

        foreach ($validatedData as $key => $value) {
            //? update or create the section title for daily offer
            SectionTitle::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        //? flash message
        toastr()->success('Updated successfully');

        return redirect()->back();
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $slider = DailyOffer::findOrFail($id);
            $slider->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
