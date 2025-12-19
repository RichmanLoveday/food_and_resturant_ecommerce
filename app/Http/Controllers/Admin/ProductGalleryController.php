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
use Illuminate\Support\Facades\DB;

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
            'image'      => 'required|image|max:3000',
            'product_id' => 'required|integer'
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Create gallery row first
            $gallery = new ProductGallery();
            $gallery->product_id = $request->product_id;
            $gallery->save(); // must save before upload

            // 2️⃣ Upload image after saving model
            $imagePath = $this->uploadImage(
                $request,
                'image',
                $gallery,
                'product-gallery'
            );

            // 3️⃣ Update gallery image path if uploaded
            if (!is_null($imagePath)) {
                $gallery->image = $imagePath;
                $gallery->save();
            }

            DB::commit();

            toastr()->success('Created Successfully');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('ProductGallery creation failed', [
                'error' => $e->getMessage(),
                'product_id' => $request->product_id
            ]);

            toastr()->error('Something went wrong while adding gallery image');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id): Response|JsonResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Fetch the gallery row
            $gallery = ProductGallery::findOrFail($id);

            // 2️⃣ Remove image from storage
            $this->removeImage(
                $gallery,
                'product-gallery'
                // $gallery->image // handled internally by removeImage
            );

            // 3️⃣ Delete DB row
            $gallery->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Deleted Successfully!'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('ProductGallery deletion failed', [
                'error' => $e->getMessage(),
                'gallery_id' => $id
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while deleting gallery image!'
            ], 500);
        }
    }
}