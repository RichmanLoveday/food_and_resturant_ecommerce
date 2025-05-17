<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductGallery;
use App\Traits\FileUploadTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductGalleryController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(string $productId)
    {
        //? get images for a specific product id
        $images = ProductGallery::where('product_id', $productId)
            ->orderBy('id', 'desc')
            ->get();
        $product = Product::findOrFail($productId);

        return view('admin.product.gallery.index', compact('productId', 'images', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|max:3000',
            'product_id' => 'required|integer'
        ]);

        $imagePath = $this->uploadImage($request, 'image', '/uploads/product-gallary');

        $gallery = new ProductGallery();
        $gallery->product_id = $request->product_id;
        $gallery->image = $imagePath;
        $gallery->save();

        toastr()->success('Created Successfully');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response|JsonResponse
    {
        try {
            //? delete image from product gallery
            $image = ProductGallery::findOrFail($id);
            $this->removeImage($image->image);
            $image->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);
        }
    }
}
