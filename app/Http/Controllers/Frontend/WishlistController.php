<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class WishlistController extends Controller
{
    public function store(Request $request): Response|JsonResponse
    {
        //? check if user is loggedIn
        if (!Auth::check()) {
            throw ValidationException::withMessages([
                'Please login to add product to wishlist!'
            ]);
        }

        //? check if product exist in wishlist 
        $productExist = Wishlist::where(['user_id' => Auth::user()->id, 'product_id' => $request->productId])
            ->exists();

        if ($productExist) {
            throw ValidationException::withMessages([
                'Product is already added to wishlist!'
            ]);
        }

        $wishList = new Wishlist();
        $wishList->user_id = Auth::user()->id;
        $wishList->product_id = $request->productId;
        $wishList->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to wishlist'
        ]);
    }

    public function wishlistProductRemove(Request $request, string|int $id): Response|JsonResponse
    {
        try {
            //? check if user is loggedIn
            if (!Auth::check()) {
                throw ValidationException::withMessages([
                    'Please login to remove product from wishlist!'
                ]);
            }

            $wishList = Wishlist::where(['user_id' => Auth::user()->id, 'id' => $id])->first();

            if (!$wishList) {
                throw ValidationException::withMessages([
                    'Product not found in wishlist!'
                ]);
            }

            $wishList->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product removed from wishlist'
            ]);
        } catch (\Exception $e) {
            logger('Unable to remove product from wishlist: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while removing the product from wishlist.',
            ], 500);
        }
    }
}
