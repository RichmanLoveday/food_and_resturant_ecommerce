<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCreateRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Traits\FileUploadTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Str;

class ProductController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(ProductDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(ProductCreateRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            /**
             * 1️⃣ Create product FIRST
             */
            $product = new Product();
            $product->name = $request->name;
            $product->slug = generateUniqueSlug('Product', $request->name);
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->offer_price = $request->offer_price ?? 0;
            $product->quantity = $request->quantity;
            $product->short_description = $request->short_description;
            $product->long_description = $request->long_description;
            $product->sku = $request->sku;
            $product->seo_title = $request->seo_title;
            $product->show_at_home = $request->show_at_home;
            $product->status = $request->status;

            // 🔥 Critical: save before upload
            $product->save();

            /**
             * 2️⃣ Upload thumbnail AFTER save
             */
            $imagePath = $this->uploadImage(
                $request,
                'image',
                $product,
                'thumbnail'
            );

            /**
             * 3️⃣ Store image path if uploaded
             */
            if (!is_null($imagePath)) {
                $product->thumb_image = $imagePath;
                $product->save(); // save again
            }

            DB::commit();

            toastr()->success('Created Successfully');
            return redirect()->route('admin.product.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Product creation failed', [
                'error' => $e->getMessage(),
            ]);

            toastr()->error('Something went wrong while creating product');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.product.edit', compact('categories', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Fetch product first
            $product = Product::findOrFail($id);

            // 2️⃣ Upload new thumbnail if provided
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImage(
                    $request,
                    'image',
                    $product,
                    'thumbnail'
                );
            }

            // 3️⃣ Update product fields
            $product->thumb_image = $imagePath ?? $product->thumb_image;
            $product->name = $request->name;
            $product->slug = $request->name !== $product->name
                ? generateUniqueSlug('Product', $request->name)
                : $product->slug;
            $product->category_id = $request->category;
            $product->price = $request->price;
            $product->offer_price = $request->offer_price ?? 0;
            $product->quantity = $request->quantity;
            $product->short_description = $request->short_description;
            $product->long_description = $request->long_description;
            $product->sku = $request->sku;
            $product->seo_title = $request->seo_title;
            $product->show_at_home = $request->show_at_home;
            $product->status = $request->status;

            $product->save();

            DB::commit();

            toastr()->success('Updated Successfully');
            return redirect()->route('admin.product.index');
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error('Product update failed', [
                'error' => $e->getMessage(),
                'product_id' => $id
            ]);

            toastr()->error('Something went wrong while updating product');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): Response|JsonResponse
    {
        try {
            $product = Product::findOrFail($id);
            $this->removeImage(
                $product,
                "thumbnail",
                // $product->thumb_image
            );
            $product->delete();

            return response()->json(['status' => 'success', 'message' => 'Deleted Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'something went wrong!']);
        }
    }
}