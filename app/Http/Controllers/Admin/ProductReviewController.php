<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductRatingDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProductRating;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    public function index(ProductRatingDataTable $dataTable): View|JsonResponse
    {
        return $dataTable->render('admin.product.product-review.index');
    }

    public function updateStatus(Request $request): JsonResponse
    {
        // dd($request->all());

        $review = ProductRating::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Updated successfully'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $review = ProductRating::findOrFail($id);
            $review->delete();

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