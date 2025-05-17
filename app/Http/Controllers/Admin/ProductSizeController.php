<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string|int $productId)
    {
        $product = Product::findOrFail($productId);
        $sizes = ProductSize::where('product_id', $product->id)->orderBy('id', 'desc')->get();
        $options = ProductOption::where('product_id', $product->id)->orderBy('id', 'desc')->get();

        return view('admin.product.product-size.index', compact('product', 'productId', 'sizes', 'options'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //? validate field
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
        ], [
            'name.required' => 'Product size name is required',
            'price.required' => 'Product size price is required',
        ]);

        $size = new ProductSize();
        $size->product_id = $request->product_id;
        $size->price = $request->price;
        $size->name = $request->name;
        $size->save();

        toastr()->success('Created Successfullly');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $size = ProductSize::findOrFail($id);
            $size->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}
